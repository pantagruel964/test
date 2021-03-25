<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user.main"})
     */
    private string $name;

    /**
     * @ORM\OneToOne(targetEntity="Balance", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"balance"})
     */
    private Balance $balance;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="sender")
     * @ORM\JoinColumn(name="id", referencedColumnName="sender_id")
     * @Groups({"out_bound_transactions"})
     */
    private Collection $outBoundTransactions;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="recipient")
     * @ORM\JoinColumn(name="id", referencedColumnName="recipient_id")
     * @Groups({"in_bound_transactions"})
     */
    private Collection $inBoundTransactions;

    public function __construct()
    {
        $this->inBoundTransactions = new ArrayCollection();
        $this->outBoundTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOutBoundTransactions(): Collection
    {
        return $this->outBoundTransactions;
    }

    public function getBalance(): Balance
    {
        return $this->balance;
    }

    public function setBalance(Balance $balance)
    {
        $this->balance = $balance;
        $balance->setUser($this);
    }
}
