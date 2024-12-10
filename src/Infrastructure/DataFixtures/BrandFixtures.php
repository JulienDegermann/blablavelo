<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Bike\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $brands = [
      'Trek',
      'Specialized',
      'Giant',
      'Cannondale',
      'Scott',
      'Canyon',
      'Merida',
      'Bianchi',
      'Pinarello',
      'Santa Cruz',
      'Colnago',
      'Orbea',
      'Cube',
      'Cervélo',
      'BMC'
  ];

    foreach ($brands as $key => $brand) {
      $current = new Brand();
      $current->setNameNumber($brand);
      $this->addReference('brand-' . $key, $current);

      $manager->persist($current);
    }

    $manager->flush();
  }
}
