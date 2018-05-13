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

class ShowMovieController extends Controller
{
  /**
  * @Route("/film/{slug}", name="show_movie")
  */
  public function showMovie(Movie $movie, Request $request)
  {

    $currentUser = $this->getUser();


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
      'comments' => $comments
    ]);
  }
}
