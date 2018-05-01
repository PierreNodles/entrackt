<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\AddMovie;
use App\Entity\Movie;

class AddMovieController extends Controller
{
  /**
  * @Route("/add/movie", name="add_movie")
  */
  public function addmovie(Request $request)
  {
    // Création de l'objet film dans lequel on va insérer les données
    $movie = new Movie();
    dump($movie);
    // Import du formulaire créé via la class AddMovie dans src/Form/addMovie.php
    $form = $this->createForm(AddMovie::class, $movie);

    // Récupération de la requête POST
    $form->handleRequest($request);

    // Traitement de la requête
    if ($form->isSubmitted() && $form->isValid()) {
      // Récupération des données du formulaire dans l'objet $movie
      $movie = $form->getData();

      // Le traitement des données avant l'enregistrement du formulaire dans la BDD se fait ob_implicit_flush
      $slug = $movie->getName();
      $slug = strtolower(trim($slug));
      $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
      $slug = preg_replace('/-+/', "-", $slug);
      dump($slug);
      $movie->setSlug($slug);
      // Récupération du Manager de Doctrine
      $entityManager = $this->getDoctrine()->getManager();
      // tell Doctrine you want to (eventually) save the movie (no queries yet)
      $entityManager->persist($movie);
      dump($movie);
      // actually executes the queries (i.e. the INSERT query)
      $entityManager->flush();
      dump($movie);
    }

    return $this->render('add_movie/index.html.twig', [
      'form' => $form->createView(),
      'movie' => $movie
    ]);
  }
}
