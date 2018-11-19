<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Movie;
use App\Form\UserEdit;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackOfficeController extends Controller
{
  /**
  * @Route("/admin/backoffice", name="back_office")
  */
  public function index(Request $request): Response
  {

    if ($request->isMethod('POST') && null !== $request->get('id') && null == $request->get('userRole') ) {
      $userId = $request->get('id');
      $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
      $em = $this->getDoctrine()->getManager();
      $em->remove($user);
      $em->flush();
    }

    if ($request->isMethod('POST') && null !== $request->get('userRole') ) {

      $role = $request->get('userRole');
      $userId = $request->get('id');

      $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
      $em = $this->getDoctrine()->getManager();
      $user->setRoles(array($role));
      $em->flush();

    }

    $userRepository = $this->getDoctrine()->getRepository(User::class);
    $users = $userRepository->findAll();

    $messageRepository = $this->getDoctrine()->getRepository(Message::class);
    $messages = $messageRepository->findAll();



    return $this->render('back_office/index.html.twig', [
      'messages' => $messages,
      'users' => $users
    ]);
  }
}
