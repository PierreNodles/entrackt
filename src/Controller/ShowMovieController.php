<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\Comment;
use App\Form\CommentType;

/**
* @Route("/film")
*/

class ShowMovieController extends Controller
{

  /**
  * @Route("/random", name="random_movie")
  */
  public function randomMovie()
  {
    $repository = $this->getDoctrine()->getRepository(Movie::class);
    $randomMovie = $repository->findOneBy([],array ('id' => 'desc'),[]);

    return $this->redirectToRoute('show_movie', array('slug' => $randomMovie->getSlug()));
  }


  /**
  * @Route("/{slug}", name="show_movie")
  */
  public function showMovie(Movie $movie, Request $request)
  {

    $currentUser = $this->getUser();


    if ($request->isMethod('POST') && $request->isXmlHttpRequest() ) {

      $response['status'] = false;
      $alreadyTaken = false;

      /*******************************************************
      Vérification pour savoir si l'user a déjà le film en favori
      ******************************************************/

      $favorites = $currentUser->getFavorite();

      foreach ($favorites as $favorite) {
        if ($favorite->getId() == $movie->getId()) {
          $alreadyTaken = true;
        }
      }
      dump($alreadyTaken);


      if (true == $alreadyTaken) {
        $failure = $movie->getName()." est déjà dans vos favoris";
        $response = [
          'status' => false,
          'failure' => $failure
        ];
      } else {
        $currentUser->addFavorite($movie);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentUser);
        $entityManager->flush();
        $success = "Le film a bien été ajouté à vos favoris";
        $response = [
          'status' => true,
          'response' => $success
        ];
      }
      return $this->json($response, JSON_UNESCAPED_UNICODE);
    }



  // 1) build the form
  $comment = new Comment();
  $form = $this->createForm(CommentType::class, $comment);

  // 2) handle the submit (will only happen on POST)
  $form->handleRequest($request);

  // MANIPULATION AVANT INSERTION DANS LA DB


  // VERIFICATION & INSERTION
  if ($form->isSubmitted() && $form->isValid()) {

    $comment->setMovie($movie);
    $comment->setUser($currentUser);

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($comment);
    $entityManager->flush();
  }

  $comments = $movie->getComments();
  foreach($comments as $comment){
    dump($comment);
  }
  return $this->render('show_movie/index.html.twig', [
    'movie' => $movie,
    'form' => $form->createView(),
    'currentUser' => $currentUser,
    'comments' => $comments,
  ]);
}
}
