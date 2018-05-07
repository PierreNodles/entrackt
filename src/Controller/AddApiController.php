<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\AddApi;
use App\Entity\Api;
use App\Entity\Movie;

class AddApiController extends Controller
{
  /**
  * @Route("/add/api", name="add_api")
  */
  public function addApi(Request $request)
  {

    // Préparation des données préalables
    $movies = [];
    $movieInserted = 0;

    // Paramètres à renvoyer
    $errors = null;
    $success = null;
    $alreadyExist = null;
    $lastInsertMovie = null;

    // tmdb_id du dernier film ajouté
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $lastInsertMovie = $repository->findBy(
      array(),
      array('tmdb_id' => 'desc'),
      1
    );
    if (!empty($lastInsertMovie)){
    $lastInsertMovieId = $lastInsertMovie[0]->getTmdb_id();
  } else {
    $lastInsertMovieId = 0;
  }


    // Traitement du formulaire d'ajout via Api

    $apiForm = new Api();
    $apiForm->setKeyApi("318652a1973949d943dbe58eff4cf2be");

    // Import du formulaire créé via la class AddApi dans src/Form/AddApi.php
    $form = $this->createForm(AddApi::class, $apiForm);

    // Récupération de la requête POST
    $form->handleRequest($request);

    // Traitement de la requête
    if ($form->isSubmitted() && $form->isValid()) {

      // Récupération des données du formulaire dans l'objet apiForm
      $apiForm = $form->getData();


      // TRAITEMENT DES DONNES DU FORMULAIRE API

      $key = $apiForm->getKeyApi();
      $amount = $apiForm->getMovieNumber();

      if (!is_numeric($amount) || $amount >= 501 || $amount == 0){
        $errors .= '<li>Le champs "Nombre de film" doit être une valeur numérique comprise entre 1 et 500</li>';
      }

      if ($key !== '318652a1973949d943dbe58eff4cf2be') {
        $errors .= "<li>La clef n'a pas été correctement renseignée</li>";
      }

      if (empty($errors)){

        ini_set('max_execution_time', 300); // Temps d'exécution maximum à 5min, la requête pouvant être longue
        // Si la base n'est pas vide, on commence l'import au film suivant le dernier ajout
        if (!empty($lastInsertMovieId)) {
          $startImport = $lastInsertMovieId +1;
        } else {
          $startImport = 1;
        }


        // L'intérêt du Do While (contrairement au foreach) est de s'assurer que la boucle se finisse quand le nombre de films récupéré est bien celui demandé par l'admin et non le nombre d'ID parcouru dans le tableau récupéré via le JSON car il y a de nombreux ID non attribué

        $i = $startImport;
        do  {
          // Vérification de l'url pour vérifier si l'api renvoie un film ou une 404
          $url ='https://api.themoviedb.org/3/movie/'.$i.'?api_key='.$key.'&language=fr';
          $headers = get_headers($url);
          $response = substr($headers[0], 9, 3);

          if ($response != "404") {
            $movies[] = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/'.$i.'?api_key='.$key.'&language=fr'), true);
            ;
          }
          $countMovies = count($movies);
          $i++;

        } while ( $countMovies < $amount );

        // Fin de la boucle
        foreach ($movies as $movie) {
          $tmdb_id = $movie['id'];

          $name = $movie["title"];

          $slug = strtolower($name);
          $slug = preg_replace('/[^a-z0-9\s]/', '', $slug);
          $slug = str_replace(' ', '-', $slug);

          $synopsis = $movie['overview'];

          $type = [];
          foreach($movie['genres'] as $movieType) {
            $type[] = $movieType["name"];
          }
          $type = implode(', ', $type);
          $release_date = $movie['release_date'];
          $rating_score = $movie['vote_average'];
          $tagline = $movie['tagline'];
          $picture = $movie['poster_path'];
          $original_title = $movie['original_title'];


          // Récupération des credits du film via l'id

          $getCredits = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_id.'/credits?api_key=318652a1973949d943dbe58eff4cf2be&language=fr'), true);

          $cast = $getCredits['cast'];

          // Avec $limit et $countCast, on s'assure que s'il y a moins de 4 acteurs dans le film, la boucle for ne tourne pas pour rien

          $limit = 4;
          $countCast = count($cast);
          if ($countCast < 5) {
            $limit = $countCast-1;
          }

          $actor = [];
          for ($i=0; $i < $limit ; $i++) {
            $actor[] = $cast[$i]['name'];
          }

          $crew = $getCredits['crew'];
          $director = [];
          $producer = [];
          foreach ($crew as $crewMember){
            if ($crewMember['job'] == 'Director'){
              $director[] = $crewMember['name'];
            } elseif ($crewMember['job'] == 'Producer'){
              $producer[] = $crewMember['name'];
            }
          }

          $actorDB = implode(', ', $actor);
          $producerDB = implode(', ', $producer);
          $directorDB = implode(', ', $director);





          // On vérifie si le film est déjà en base de données avant de l'insérer
          $movietoCheck = $this->getDoctrine()
          ->getRepository(Movie::class)
          ->checkDB($tmdb_id);

          if($movietoCheck == null ) {
            $addMovie = new Movie();
            $addMovie->setTmdb_id($tmdb_id);
            $addMovie->setName($name);
            $addMovie->setOriginalTitle($original_title);
            $addMovie->setSlug($slug);
            $addMovie->setReleaseDate($release_date);
            $addMovie->setRatingScore($rating_score);
            $addMovie->setType($type);
            $addMovie->setSynopsis($synopsis);
            $addMovie->setPicture($picture);
            $addMovie->setTagline($tagline);
            $addMovie->setActors($actorDB);
            $addMovie->setDirector($directorDB);
            $addMovie->setProducer($producerDB);


            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($addMovie);

            $entityManager->flush();
            $movieInserted++;


          } else {
            $alreadyExist .= "<li> Le film '$name' est déjà dans la base de données</li>";
          }

        } //end foreach
        dump($movieInserted);
        $number = $movieInserted;
        if ($number > 1) {
          $success = " $number films ont bien été ajoutés à la base de données.";
        } elseif ($number == 0){
          $success = "L'opération s'est bien déroulée, néanmoins il n'y a pas de nouveau film dont l'id correspond à votre demande. Essayez d'ajouter plus de film";
        } else  {
          $success = "Le film <b>$name</b> a bien été ajouté à la base de données.";
        }

      }


    }


    return $this->render('add_api/index.html.twig', [
      'form' => $form->createView(),
      'success' => $success,
      'errors' => $errors,
      'alreadyExist' => $alreadyExist
    ]);
  }
}
