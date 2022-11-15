<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Repository\UserRepository;

class AuthController extends AbstractController
{
    #[Route('/login2', name: 'api_login')]
    public function login(UserRepository $userRepository, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        $u = $userRepository->find(3);

    //dd($u);

        return new JsonResponse(['token' => $JWTManager->create($u)]);

        /*
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], 401);
        }
        $token = 'TOKEN';

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $token,
        ]);

        public function getTokenUser(UserInterface $user, JWTTokenManagerInterface $JWTManager): JsonResponse
        {
            return new JsonResponse(['token' => $JWTManager->create($user)]);
        } */
    }
}
