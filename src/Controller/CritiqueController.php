<?php

namespace App\Controller;


use App\Form\CritiqueType;
use App\Entity\User;
use App\Entity\Critique;
use App\Repository\CritiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;

/**
* @Route("/critique")
*/
class CritiqueController extends Controller
{
  /**
  * @Route("/", name="critique_index", methods="GET")
  */
  public function index(CritiqueRepository $critiqueRepository): Response
  {
    return $this->render('critique/index.html.twig', ['critiques' => $critiqueRepository->findAll()]);
  }



  /**
  * @Route("/ajouter", name="critique_new", methods="GET|POST")
  */
  public function new(Request $request, FileUploader $fileUploader): Response
  {

    $currentUser = $this->getUser();

    $critique = new Critique();
    $form = $this->createForm(CritiqueType::class, $critique);
    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {


      $file = $form->get('image')->getData();
      $fileName = $fileUploader->upload($file);
      $critique->setImage($fileName);


      $critique->setUser($currentUser);
      $name = $critique->getMovie()->getName();

      $slug = strtolower($name);
      $slug = preg_replace('/[^a-z0-9\s]/', '', $slug);
      $slug = str_replace(' ', '-', $slug);

      $critique->setSlug($slug);





      $em = $this->getDoctrine()->getManager();
      $em->persist($critique);
      $em->flush();
    }

    return $this->render('critique/new.html.twig', [
      'critique' => $critique,
      'form' => $form->createView(),
    ]);
  }


  /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }




  /**
  * @Route("/{slug}", name="critique_show", methods="GET")
  */
  public function show(Critique $critique): Response
  {
    return $this->render('critique/show.html.twig', ['critique' => $critique]);
  }

  /**
  * @Route("/{id}/edit", name="critique_edit", methods="GET|POST")
  */
  public function edit(Request $request, Critique $critique): Response
  {
    $form = $this->createForm(CritiqueType::class, $critique);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();

      return $this->redirectToRoute('critique_edit', ['id' => $critique->getId()]);
    }

    return $this->render('critique/edit.html.twig', [
      'critique' => $critique,
      'form' => $form->createView(),
    ]);
  }

  /**
  * @Route("/{id}", name="critique_delete", methods="DELETE")
  */
  public function delete(Request $request, Critique $critique): Response
  {
    if ($this->isCsrfTokenValid('delete'.$critique->getId(), $request->request->get('_token'))) {
      $em = $this->getDoctrine()->getManager();
      $em->remove($critique);
      $em->flush();
    }

    return $this->redirectToRoute('critique_index');
  }
}
