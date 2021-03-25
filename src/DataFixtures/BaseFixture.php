<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    private const LOCALE = 'ru_RU';

    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create(self::LOCALE);
    }
}
