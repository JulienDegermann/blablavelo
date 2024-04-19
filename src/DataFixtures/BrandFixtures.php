<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


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
