<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Form\AddApi;
use App\Entity\Api;

class AddApiController extends Controller
{
    /**
     * @Route("/add/api", name="add_api")
     */
    public function addApi()
    {
      $apiForm = new Api();

       $form = $this->createForm(AddApi::class, $apiForm);

        return $this->render('add_api/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
