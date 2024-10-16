<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\PracticeDetail\Mind;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MindFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      $minds = [
        'Ballade',
        'Loisir',
        'Compétition'
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
