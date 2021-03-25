<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use OpenApi\Annotations as OA;

class UserApiController extends AbstractController
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @OA\Get(
     *  path="/api/users",
     *  @OA\Response(
     *     response="200",
     *     description="Return all users with their balance",
     *  )
     * )
     */
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json(['users' => $users], JsonResponse::HTTP_OK, [], ['groups' => ['user.main', 'balance']]);
    }

    /**
     * @OA\Get(
     *  path="/api/users/{id}",
     *  @OA\Response(
     *     response="200",
     *     description="Return user by id with balance",
     *  )
     * )
     */
    public function getOne(User $user): JsonResponse
    {
        return $this->json(['user' => $user], JsonResponse::HTTP_OK, [], ['groups' => ['user.main', 'balance']]);
    }
}
