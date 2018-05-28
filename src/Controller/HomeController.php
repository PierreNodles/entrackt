<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Critique;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

      $currentUser = $this->getUser();

      $repository = $this->getDoctrine()->getRepository(Movie::class);
      $lastInsertedMovies = $repository->findBy([],array ('id' => 'desc'), $limit = 12,[]);

      $repository = $this->getDoctrine()->getRepository(Critique::class);
      $lastInsertedCritiques = $repository->findBy([],array ('id' => 'desc'), $limit = 2,[]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lastInsertedMovies' => $lastInsertedMovies,
            'lastInsertedCritiques' => $lastInsertedCritiques,
            'currentUser' => $currentUser,
        ]);
    }
}
