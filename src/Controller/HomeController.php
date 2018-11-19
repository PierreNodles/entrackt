<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\User;
use App\Entity\Critique;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {

      $currentUser = $this->getUser();

      $repository = $this->getDoctrine()->getRepository(Movie::class);
      $lastInsertedMovies = $repository->findBy([],array ('id' => 'desc'), $limit = 12,[]);

      $repository = $this->getDoctrine()->getRepository(Critique::class);
      $lastInsertedCritiques = $repository->findBy([],array ('id' => 'desc'), $limit = 2,[]);


      if ($request->isMethod('POST')) {

        $entityManager = $this->getDoctrine()->getManager();
        $message = new Message();

        $content = $request->get('message');

         if  (null == $currentUser) {
           $email = $request->get('email');
         } else {
           $user = $currentUser;
           $email = $currentUser->getEmail();
          $message->setUserId($user);
         }

         $message->setEmail($email);
         $message->setMessage($content);

         $entityManager->persist($message);
         $entityManager->flush();

       }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'lastInsertedMovies' => $lastInsertedMovies,
            'lastInsertedCritiques' => $lastInsertedCritiques,
            'currentUser' => $currentUser,
        ]);
    }
}
