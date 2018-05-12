<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\User;

class ShowUserController extends Controller
{
    /**
     * @Route("/utilisateur/{slug}", name="show_user")
     */
    public function show(User $user)
    {
        return $this->render('show_user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
