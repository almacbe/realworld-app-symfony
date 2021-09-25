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
        // TODO: Validate username, email and even password
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
    public function currentUser(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->json($this->serializeUser($user, $request));
    }

    /**
     * @Route("/user", name="update", methods={"PUT"})
     */
    public function update(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        $userData = json_decode($request->getContent(), true)['user'];

        // TODO: Validate if email or username are uniques or not.

        $update = false;
        if (isset($userData['email'])) {
            $user->setEmail($userData['email']);
            $update = true;
        }

        if (isset($userData['username'])) {
            $user->setUsername($userData['username']);
            $update = true;
        }

        if (isset($userData['password'])) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $userData['password']
            ));
            $update = true;
        }

        if ($update) {
            $userRepository->save($user);
        }

        return $this->json($this->serializeUser($user, $request));
    }

    private function getTokenFromAuthorizationHeader(Request $request): string
    {
        return explode(' ', $request->headers->get('Authorization'))[1];
    }

    /**
     * @param User $user
     * @param Request $request
     * @return array[]
     */
    private function serializeUser(User $user, Request $request): array
    {
        return [
            'user' => [
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'bio' => '',
                'image' => '',
                'token' => $this->getTokenFromAuthorizationHeader($request),
            ]
        ];
    }
}
