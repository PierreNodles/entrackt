<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Movie;

class FilterMovieController extends Controller
{
    /**
     * @Route("/filtrer", name="filter_movie")
     */
    public function index()
    {

      $repository = $this->getDoctrine()->getRepository(Movie::class);
      $movies = $repository->findBy([],[], $limit = 50,[]);

      foreach($movies as $movie) {
         $typeArray = explode(', ', $movie->getType());
         foreach($typeArray as $key => $value){
            $types[] = $value;
         }
      }
      $types = array_unique($types);
      dump($types);


        return $this->render('filter_movie/index.html.twig', [
            'movies' => $movies,
            'types' => $types
        ]);
    }
}
