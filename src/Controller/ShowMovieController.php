<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Movie;

class ShowMovieController extends Controller
{
  /**
  * @Route("/film/{slug}", name="show_movie")
  */
  public function showMovie(Movie $movie)
  {

    // On définit les variables qu'on renverra à twig
    $comments = null;
    $errors = [];
    $success = '';

    dump($movie);

    return $this->render('show_movie/index.html.twig', [
      'movie' => $movie,
    ]);
  }
}
