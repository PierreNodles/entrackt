<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminLoginController extends Controller
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function index()
    {
        return $this->render('admin_login/index.html.twig', [
            'controller_name' => 'AdminLoginController',
        ]);
    }
}
