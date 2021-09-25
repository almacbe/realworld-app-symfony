<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="register", methods={"POST"})
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository,
        JWTTokenManagerInterface $tokenManager
    ): Response {
        $userData = json_decode($request->getContent(), true)['user'];

        $user = new User();
        $user
            ->setUsername($userData['username'])
            ->setEmail($userData['email'])
            ->setRoles(['ROLE_USER'])
            ->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userData['password']
                )
            )
        ;

        $userRepository->save($user);

        return $this->json([
            'user' => [
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'bio' => '',
                'image' => '',
                'token' => $tokenManager->create($user),
            ]
        ]);
    }

    /**
     * TODO: Overwrite resquest & response of login
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
