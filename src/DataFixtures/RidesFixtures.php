<?php

namespace App\DataFixtures;

use App\Entity\Ride;
use DateTimeImmutable;
use App\DataFixtures\CityFixtures;
use App\DataFixtures\MindFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ModelFixtures;
use App\DataFixtures\PracticeFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
// use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class RideFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $rides = [
      [
        'title' => 'Titre de la sortie 1',
        'place' => 'Départ de la sortie 1'
      ],
      [
        'title' => 'Titre de la sortie ',
        'place' => 'Départ de la sortie '
      ],
      [
        'title' => 'Titre de la sortie ',
        'place' => 'Départ de la sortie '
      ],
      [
        'title' => 'Titre de la sortie ',
        'place' => 'Départ de la sortie '
      ]
    ];

    foreach ($rides as $key => $ride) {
      $current = new Ride();
      $current->setTitle($ride['title']);
      $current->setDistance(rand(20, 80));
      $current->setAscent(rand(200, 1200));
      $current->setMaxRider(rand(1, 10));
      $current->setAverageSpeed(rand(10, 40));
      $current->setPlace($ride['place']);
      $current->setMind($this->getReference('mind-' . rand(0, 2)));
      $current->setPractice($this->getReference('practice-' . rand(0, 4)));
      $current->setDate(new DateTimeImmutable());


      // add creator
      $current->setUserCreator($this->getReference('user-' . rand(0, 5)));

      //add new participant
      for ($i = 1; $i < $current->getMaxRider(); $i++) {
        $current->addUserParticipant($this->getReference('user-' . rand(0, 8)));
      }
      $this->addReference('ride-' . $key, $current);

      $manager->persist($current);
    }

    $manager->flush();
  }
  public function getDependencies()
  {
    return [
      MindFixtures::class,
      PracticeFixtures::class,
      UserFixtures::class,
    ];
  }
}
