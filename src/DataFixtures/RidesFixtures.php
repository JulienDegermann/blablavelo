<?php

namespace App\DataFixtures;

use App\Entity\Ride;
use DateTime;
use App\DataFixtures\CityFixtures;
use App\DataFixtures\MindFixtures;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\PracticeFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class RideFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    $rides = [
      [
        'title' => 'Titre de la sortie 1',
        'description' => 'Description de la sortie 1'
      ],
      [
        'title' => 'Titre de la sortie 2',
        'description' => 'Description de la sortie 2'
      ],
      [
        'title' => 'Titre de la sortie 3',
        'description' => 'Description de la sortie 3'
      ],
      [
        'title' => 'Titre de la sortie 4',
        'description' => 'Description de la sortie 4'
      ],
      [
        'title' => 'Titre de la sortie 5',
        'description' => 'Description de la sortie 5'
      ],
      [
        'title' => 'Titre de la sortie 6',
        'description' => 'Description de la sortie 6'
      ],
      [
        'title' => 'Titre de la sortie 7',
        'description' => 'Description de la sortie 7'
      ],
      [
        'title' => 'Titre de la sortie 8',
        'description' => 'Description de la sortie 8'
      ],
      [
        'title' => 'Titre de la sortie 9',
        'description' => 'Description de la sortie 9'
      ],
      [
        'title' => 'Titre de la sortie 10',
        'description' => 'Description de la sortie 10'
      ],
      [
        'title' => 'Titre de la sortie 11',
        'description' => 'Description de la sortie 11'
      ],
      [
        'title' => 'Titre de la sortie 12',
        'description' => 'Description de la sortie 12'
      ],
      [
        'title' => 'Titre de la sortie 13',
        'description' => 'Description de la sortie 13'
      ],
      [
        'title' => 'Titre de la sortie 14',
        'description' => 'Description de la sortie 14'
      ],
      [
        'title' => 'Titre de la sortie 15',
        'description' => 'Description de la sortie 15'
      ],
    ];

    foreach ($rides as $key => $ride) {
      $current = new Ride();
      $current->setTitle($ride['title']);
      $current->setDescription($ride['description']);
      $current->setDistance(rand(20, 80));
      $current->setAscent(rand(200, 1200));
      $current->setMaxRider(rand(2, 10));
      $current->setAverageSpeed(rand(10, 40));
      $current->setCity($this->getReference('city' . rand(0, 30)));
      $current->setMind($this->getReference('mind-' . rand(0, 2)));
      $current->setPractice($this->getReference('practice-' . rand(0, 4)));
      $current->setDate(new DateTime());
      // add creator
      $current->setUserCreator($this->getReference('user-' . rand(0, 5)));
      //add new participant
      $current->addUserParticipant($current->getUserCreator());
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
      CityFixtures::class,
      MindFixtures::class,
      PracticeFixtures::class,
      UserFixtures::class,
    ];
  }
}
