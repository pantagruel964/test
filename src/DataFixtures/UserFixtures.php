<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Balance;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<=100;$i++) {
            $user = new User();
            $user->setName($this->faker->name());

            $balance = new Balance();
            $balance->setAmount(10000);

            $user->setBalance($balance);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
