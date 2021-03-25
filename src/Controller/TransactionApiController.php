<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MoneyTransfer;
use App\Entity\User;
use App\Repository\TransactionRepository;
use App\Service\MoneyTransferService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use OpenApi\Annotations as OA;

class TransactionApiController extends AbstractController
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private MoneyTransferService $moneyTransferService
    ) {
    }

    /**
     * @OA\Get(
     *  path="/api/users/{id}/transactions",
     *  @OA\Response(
     *     response="200",
     *     description="Return outbound transactions by user",
     *  )
     * )
     */
    public function getOutBoundByUser(User $user): JsonResponse
    {
        $transactions = $this->transactionRepository->findBy(['sender' => $user]);

        return $this->json(
            ['transactions' => $transactions],
            JsonResponse::HTTP_OK,
            [],
            [
                'groups' => ['transaction.main', 'transaction.recipient', 'user.main'],
                DateTimeNormalizer::FORMAT_KEY => 'd.m.Y H:m:i'
            ]
        );
    }

    /**
     * @OA\Post(
     *  path="/api/users/{id}/transactions",
     *  @OA\RequestBody(
     *     @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              @OA\Property(property="recipient_id", type="integer"),
     *              @OA\Property(property="amount", type="integer")
     *          )
     *     )
     *  ),
     *  @OA\Response(
     *     response="201",
     *     description="Transfer money to another user",
     *  )
     * )
     */
    public function sendToUser(MoneyTransfer $moneyTransfer): JsonResponse
    {
        $this->moneyTransferService->sendToUser($moneyTransfer);

        return $this->json(['message' => 'The transfer was completed successfully.'], JsonResponse::HTTP_CREATED);
    }
}
