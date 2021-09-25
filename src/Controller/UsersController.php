<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="register", methods={"POST"})
     */
    public function register(): Response
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

    /**
     * TODO: Overwrite response of login
     * Route("/users/login", name="login", methods={"POST"})
     */
    public function login(): Response
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

    /**
     * @Route("/user", name="current_user", methods={"GET"})
     */
    public function currentUser(): Response
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

    /**
     * @Route("/user", name="current_user", methods={"PUT"})
     */
    public function update(): Response
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
