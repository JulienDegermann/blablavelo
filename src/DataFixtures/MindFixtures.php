<?php

namespace App\DataFixtures;

use App\Entity\Mind;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class MindFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $minds = [
        'Ballade',
        'Loisir',
        'Perf\''
    ];
        foreach($minds as $key => $mind) {
            $current = new Mind();
            $current->setName($mind);
            $this->addReference('mind-' . $key, $current);

            $manager->persist($current);
        }

        $manager->flush();
    }
}
