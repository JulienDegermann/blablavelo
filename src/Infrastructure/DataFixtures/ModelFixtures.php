<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Bike\Model;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModelFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $models = [
      'Trek' => [
        'Domane',
        'Madone',
        'Fuel EX',
        'Emonda',
        'Top Fuel',
      ],
      'Specialized' => [
        'Roubaix',
        'Tarmac',
        'Stumpjumper',
        'Epic',
        'Sirrus',
      ],
      'Giant' => [
        'Defy',
        'TCR',
        'Trance',
        'Anthem',
        'Escape',
      ],
      'Cannondale' => [
        'Synapse',
        'SuperSix EVO',
        'Scalpel',
        'Trail',
        'Quick',
      ],
      'Scott' => [
        'Addict',
        'Foil',
        'Spark',
        'Genius',
        'Speedster',
      ],
      'Canyon' => [
        'Ultimate',
        'Aeroad',
        'Neuron',
        'Lux',
        'Endurace',
      ],
      'Merida' => [
        'Reacto',
        'Scultura',
        'One-Twenty',
        'Big.Nine',
        'Crossway',
      ],
      'Bianchi' => [
        'Oltre',
        'Specialissima',
        'Methanol',
        'Impulso',
        'Via Nirone',
      ],
      'Pinarello' => [
        'Dogma',
        'Prince',
        'Dyodo',
        'Gan',
        'Nytro',
      ],
      'Santa Cruz' => [
        'Tallboy',
        'Hightower',
        'Bronson',
        'Nomad',
        '5010',
      ],
      'Colnago' => [
        'C64',
        'V3RS',
        'Concept',
        'Prestige',
        'Master',
      ],
      'Orbea' => [
        'Orca',
        'Gain',
        'Alma',
        'Occam',
        'Terra',
      ],
      'Cube' => [
        'Agree',
        'Stereo',
        'Reaction',
        'Access',
        'Travel',
      ],
      'Cervélo' => [
        'R5',
        'S3',
        'Aspero',
        'P5',
        'Caledonia',
      ],
      'BMC' => [
        'Teammachine',
        'Roadmachine',
        'Fourstroke',
        'Agonist',
        'Alpenchallenge',
      ],
    ];
    
    $i = 0;

    foreach ($models as $key => $model) {
      foreach ($model as $modelItem) {
        $current = new Model();
        $current->setNameNumber($modelItem);
        $current->setYear(rand(1950, 2021));
        $current->setBrand($this->getReference('brand-' . rand(1, 14)));
        $this->addReference('model-' . $i, $current);

        $manager->persist($current);
        $i ++;
      }
    }

    $manager->flush();
  }

  public function getDependencies(): array
  {
      return [
          BrandFixtures::class,
      ];
  }
}
