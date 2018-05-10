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

      $movieName = $movie->getName();

        return $this->render('show_movie/index.html.twig', [
            'movie_name' => $movieName,
        ]);
    }
}
