<?php

namespace App\DataFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

    }
}
