<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\User;
use App\Entity\Movie;

class ShowUserController extends Controller
{
  /**
  * @Route("/utilisateur/{slug}", name="show_user")
  */
  public function show(User $user)
  {

  $types = [];
  $movies = $user->getFavorite();
  if ($movies) {
    foreach($movies as $movie) {
       $typeArray = explode(', ', $movie->getType());
       foreach($typeArray as $key => $value){
          $types[] = $value;
       }
    }
  }

  $types = array_unique($types);

    return $this->render('show_user/index.html.twig', [
      'user' => $user,
      'movies' => $movies,
      'types' => $types
    ]);
  }
}
