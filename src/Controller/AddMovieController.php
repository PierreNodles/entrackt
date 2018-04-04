<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Movie;

class AddMovieController extends Controller
{
    /**
     * @Route("/add/movie", name="add_movie")
     */
    public function index()
    {
      $repository = $this->getDoctrine()->getRepository(Movie::class);

      if  ( !$repository->findOneBy(["name" => "Autant en emporte le vent"]) ) {
      // you can fetch the EntityManager via $this->getDoctrine()
       // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
       $entityManager = $this->getDoctrine()->getManager();

       $movie = new Movie();
       $name = $movie->setName('Autant en emporte le vent');
       $movie->setSlug("autant-en-emporte-le-vent");
       $movie->setSynopsis('Un très bon film !');

       // tell Doctrine you want to (eventually) save the movie (no queries yet)
       $entityManager->persist($movie);

       // actually executes the queries (i.e. the INSERT query)
       $entityManager->flush();
    } else {
      $name = $repository->findOneBy(["name" => "Autant en emporte le vent"])->getName();
   }

        return $this->render('add_movie/index.html.twig', [
            'name' => $name,
        ]);
    }
}
