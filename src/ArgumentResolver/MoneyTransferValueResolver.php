<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Entity\MoneyTransfer;
use App\Entity\User;
use App\Exception\NotEnoughMoneyException;
use App\Exception\UserNotFoundException;
use App\Exception\UserSendTransferHimselfException;
use App\Repository\UserRepository;
use Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class MoneyTransferValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return MoneyTransfer::class === $argument->getType();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $sender = $this->getSender((int)$request->attributes->get('id'));
        $recipient = $this->getRecipient((int)$request->request->get('recipient_id'));

        if ($sender->getId() === $recipient->getId()) {
            throw new UserSendTransferHimselfException('Sender and recipient must be different', JsonResponse::HTTP_BAD_REQUEST);
        }

        $amount = (float)$request->request->get('amount');

        if ($sender->getBalance()->getAmount() < $amount) {
            throw new NotEnoughMoneyException('Not enough money', JsonResponse::HTTP_BAD_REQUEST);
        }

        yield new MoneyTransfer($sender, $recipient, $amount);
    }

    private function getSender(int $id): User
    {
        $sender = $this->userRepository->find($id);

        if (!$sender) {
            throw new UserNotFoundException('Sender not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return $sender;
    }

    private function getRecipient(int $id): User
    {
        $recipient = $this->userRepository->find($id);

        if (!$recipient) {
            throw new UserNotFoundException('Recipient not found', JsonResponse::HTTP_NOT_FOUND);
        }

        return $recipient;
    }
}
