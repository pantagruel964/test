<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransactionRepository;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"sender_id", "recipient_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Transaction
{
    public const STATUS_SUCCESS = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transaction.main"})
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="outBoundTransactions")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id", nullable=false)
     * @Groups("transaction.sender")
     */
    private User $sender;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="inBoundTransactions")
     * @ORM\JoinColumn(name="recipient_id", nullable=false)
     * @Groups({"transaction.recipient"})
     */
    private User $recipient;

    /**
     * @ORM\Column(type="decimal")
     * @Groups({"transaction.main"})
     */
    private float $amount;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transaction.main"})
     */
    private int $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"transaction.main"})
     */
    private ?DateTimeImmutable $created_at = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $updated_at = null;

    public function __construct(
        User $sender,
        User $recipient,
        float $amount,
        int $status,
    ) {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->amount = $amount;
        $this->status = $status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $dateTime = CarbonImmutable::now();

        $this->updated_at = $dateTime;

        if (null === $this->created_at) {
            $this->created_at = $dateTime;
        }
    }
}
