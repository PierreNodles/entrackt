<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowMovieController extends Controller
{
    /**
     * @Route("/show/movie", name="show_movie")
     */
    public function index()
    {
        return $this->render('show_movie/index.html.twig', [
            'controller_name' => 'ShowMovieController',
        ]);
    }
}
