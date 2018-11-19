<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movie;
use App\Form\UserEdit;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', ['users' => $userRepository->findAll()]);
    }

    /**
     * @Route("/new", name="user_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserEdit::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/{slug}", name="show_user")
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

    /**
     * @Route("/{slug}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserEdit::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_edit', ['slug' => $user->getSlug()]);
        }

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

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'success' => "Succès",
            'movies' => $movies,
            'types' => $types
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods="DELETE")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
