<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\MindFixtures;
use App\DataFixtures\ModelFixtures;
use App\DataFixtures\PracticeFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
  public function __construct(
    private UserPasswordHasherInterface $userPasswordHasherInterface
  ) {
    $this->userPasswordHasherInterface = $userPasswordHasherInterface;
  }

  public function load(ObjectManager $manager): void
  {
    $users = [
      [
        'first_name' => 'John&',
        'last_name' => 'Doe',
        'user_name' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'password123',
      ],
      [
        'first_name' => 'Alice',
        'last_name' => 'Smith',
        'user_name' => 'alicesmith',
        'email' => 'alice@example.com',
        'password' => 'password456',
      ],
      [
        'first_name' => 'Bob',
        'last_name' => 'Johnson',
        'user_name' => 'bobjohnson',
        'email' => 'bob@example.com',
        'password' => 'password789',
      ],
      [
        'first_name' => 'Sarah',
        'last_name' => 'Brown',
        'user_name' => 'sarahbrown',
        'email' => 'sarah@example.com',
        'password' => 'passwordabc',
      ],
      [
        'first_name' => 'Michael',
        'last_name' => 'Wilson',
        'user_name' => 'michaelwilson',
        'email' => 'michael@example.com',
        'password' => 'passwordxyz',
      ],
      [
        'first_name' => 'Emily',
        'last_name' => 'Lee',
        'user_name' => 'emilylee',
        'email' => 'emily@example.com',
        'password' => 'password12345',
      ],
      [
        'first_name' => 'David',
        'last_name' => 'Martinez',
        'user_name' => 'davidmartinez',
        'email' => 'david@example.com',
        'password' => 'password6789',
      ],
      [
        'first_name' => 'Linda',
        'last_name' => 'Taylor',
        'user_name' => 'lindataylor',
        'email' => 'linda@example.com',
        'password' => 'password3456',
      ],
      [
        'first_name' => 'Daniel',
        'last_name' => 'Walker',
        'user_name' => 'danielwalker',
        'email' => 'daniel@example.com',
        'password' => 'passwordlmn',
      ],
      [
        'first_name' => 'Olivia',
        'last_name' => 'Garcia',
        'user_name' => 'oliviagarcia',
        'email' => 'olivia@example.com',
        'password' => 'passwordopq',
      ],
    ];


    $admin = (new User())
      ->setNameNumber('Admin')
      ->setEmail('admin@admin.com')
      ->setFirstName('Admin')
      ->setLastName('Admin')
      ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
    $admin->setPassword($this->userPasswordHasherInterface->hashPassword($admin, 'admin'));
    $manager->persist($admin);


    foreach ($users as $key => $user) {
      $current = new User();
      $current->setNameNumber($user['user_name']);
      $current->setEmail($user['email']);
      $current->setFirstName($user['first_name']);
      $current->setLastName($user['last_name']);
      $current->setRoles(['ROLE_USER']);

      // $current->setPassword($user['password']);
      $current->setPassword($this->userPasswordHasherInterface->hashPassword($current, $user['password']));

      // $current->setBirthDate(new DateTimeImmutable());
      // $current->setBike($this->getReference('model-' . rand(0, 20)));
      $current->setPractice($this->getReference('practice-' . rand(0, 3)));
      $current->setMind($this->getReference('mind-' . rand(0, 2)));
      // $current->setDepartment($this->getReference('department-' . rand(0, 90)));
      $this->addReference('user' . $key, $current);

      $manager->persist($current);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      MindFixtures::class,
      PracticeFixtures::class,
      ModelFixtures::class,
    ];
  }
}
