<?php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
  /**
  * @Route("/register", name="user_registration")
  */
  public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
  {
    // 1) build the form
    $user = new User();
    $form = $this->createForm(UserType::class, $user);

    // 2) handle the submit (will only happen on POST)
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      // 3) Encode the password (you could also do this via Doctrine listener)
      $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
      $user->setPassword($password);


      // 4) save the User!
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($user);
      $entityManager->flush();
      $success = "Vous êtes bien inscrit";
      $response = [
                  'status' => true,
                  'response' => $success
               ];
      // ... do any other work - like sending them an email, etc
      // maybe set a "flash" success message for the user
      if ($request->isXmlHttpRequest()){
         return $this->json($response, JSON_UNESCAPED_UNICODE);
      }
    }



    return $this->render(
      'registration/register.html.twig',
      array('form' => $form->createView())
    );
  }
}
