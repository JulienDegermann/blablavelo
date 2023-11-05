<?php

namespace App\DataFixtures;

use App\Entity\Practice;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class PracticeFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $practices = [
      'VTT roulant',
      'VTT engagé',
      'Gravel',
      'Route',
      'Bike Packing',
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
