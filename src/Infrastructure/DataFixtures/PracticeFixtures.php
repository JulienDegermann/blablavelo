<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\PracticeDetail\Practice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PracticeFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $practices = [
      'VTT Rando',
      'VTT Randuro',
      'Gravel',
      'Route'
    ];
    foreach ($practices as $key => $practice) {
      $current = new Practice();
      $current->setName($practice);
      $this->addReference('practice-' . $key, $current);

      $manager->persist($current);
    }

    $manager->flush();
  }
}
