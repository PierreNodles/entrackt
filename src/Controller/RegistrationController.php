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

    // Gestion des erreurs et de la ResponseAjax
    $errors = false;
    $success = '';
    $response['status'] = false;

    // 2) handle the submit (will only happen on POST)
    $form->handleRequest($request);


  // VERIFICATION AVANT INSERTION DANS LA DB

    // Recupération des données dans l'object $user
    $email = $user->getEmail();
    $username = $user->getUsername();
    $password = $user->getPlainPassword();

    // Validations


    if (!$username) {
      $errors = true;
      $response['username'] = "Le nom d'utilisateur ne peut pas être laissé vide";
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors = true;
      $response['emailNotValid'] = "L'adresse email n'est pas valide";
    }

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);

    if( !$uppercase || !$lowercase || !$number || strlen($password) < 8) {
      $errors = true;
      $response['passwordNotValid'] = "Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une miniscule et un chiffre";
    }

    //
    if ($form->isSubmitted() && $form->isValid() && false == $errors) {
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
    }

    if ($request->isXmlHttpRequest() && $response['status'] == false && $errors == false){
      return $this->render(
        'registration/register.html.twig',
        array('form' => $form->createView())
      );
    }

    if ($request->isXmlHttpRequest()){
      return $this->json($response, JSON_UNESCAPED_UNICODE);
    }



    return $this->render(
      'registration/register.html.twig',
      array('form' => $form->createView())
    );
  }
}
