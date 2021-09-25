<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="user")
     */
    public function index(): Response
    {
        return $this->json([
            'user' => [
                'email' => '',
                'username' => '',
                'bio' => '',
                'image' => '',
                'token' => '',
            ]
        ]);
    }
}
