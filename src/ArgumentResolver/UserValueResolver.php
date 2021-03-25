<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return User::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $user = $this->userRepository->find($request->attributes->get('id'));

        if (!$user) {
            throw new UserNotFoundException('User not found', JsonResponse::HTTP_NOT_FOUND);
        }

        yield $user;
    }
}
