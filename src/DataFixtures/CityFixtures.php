<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\DepartmentFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CityFixtures extends Fixture implements DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {

    $morbihan = [
      ["zip_code" => "56000", "name" => "Vannes"],
      ["zip_code" => "56100", "name" => "Lorient"],
      ["zip_code" => "56600", "name" => "Lanester"],
      ["zip_code" => "56270", "name" => "Ploemeur"],
      ["zip_code" => "56700", "name" => "Hennebont"],
      ["zip_code" => "56400", "name" => "Auray"],
      ["zip_code" => "56300", "name" => "Pontivy"],
      ["zip_code" => "56890", "name" => "Saint-Avé"],
      ["zip_code" => "56520", "name" => "Guidel"],
      ["zip_code" => "56530", "name" => "Quéven"],
      ["zip_code" => "56800", "name" => "Ploërmel"],
      ["zip_code" => "56370", "name" => "Sarzeau"],
      ["zip_code" => "56260", "name" => "Larmor-Plage"],
      ["zip_code" => "56290", "name" => "Port-Louis"],
      ["zip_code" => "56150", "name" => "Baud"],
      ["zip_code" => "56860", "name" => "Séné"],
      ["zip_code" => "56390", "name" => "Grand-Champ"],
      ["zip_code" => "56330", "name" => "Pluvigner"],
      ["zip_code" => "56230", "name" => "Questembert"],
      ["zip_code" => "56240", "name" => "Plouay"],
      ["zip_code" => "56470", "name" => "La Trinité-sur-Mer"],
      ["zip_code" => "56410", "name" => "Étel"],
      ["zip_code" => "56340", "name" => "Carnac"],
      ["zip_code" => "56400", "name" => "Brech"],
      ["zip_code" => "56600", "name" => "Riantec"],
      ["zip_code" => "56920", "name" => "Noyal-Pontivy"],
      ["zip_code" => "56140", "name" => "Malestroit"],
      ["zip_code" => "56190", "name" => "Muzillac"],
      ["zip_code" => "56400", "name" => "Pluneret"],
      ["zip_code" => "56660", "name" => "Saint-Jean-Brévelay"],
      ["zip_code" => "56610", "name" => "Arradon"],
      ["zip_code" => "56500", "name" => "Locminé"],
      ["zip_code" => "56890", "name" => "Plescop"],
      ["zip_code" => "56680", "name" => "Plouhinec"],
      ["zip_code" => "56350", "name" => "Allaire"],
      ["zip_code" => "56250", "name" => "Elven"],
      ["zip_code" => "56320", "name" => "Le Palais (Belle-Île-en-Mer)"],
      ["zip_code" => "56130", "name" => "La Roche-Bernard"],
      ["zip_code" => "56300", "name" => "La Chapelle-Neuve"],
      ["zip_code" => "56200", "name" => "La Gacilly"],
      ["zip_code" => "56170", "name" => "Quiberon"],
      ["zip_code" => "56870", "name" => "Baden"],
      ["zip_code" => "56340", "name" => "Plouharnel"],
      ["zip_code" => "56420", "name" => "Plumelec"],
      ["zip_code" => "56400", "name" => "Bono"],
      ["zip_code" => "56920", "name" => "Crach"],
      ["zip_code" => "56330", "name" => "Camors"],
      ["zip_code" => "56430", "name" => "Languidic"],
      ["zip_code" => "56470", "name" => "Saint-Philibert"],
      ["zip_code" => "56680", "name" => "Plouhinec"],
    ];
    $departments = [
      "01",
      "02",
      "03",
      "04",
      "05",
      "06",
      "07",
      "08",
      "09",
      "10",
      "11",
      "12",
      "13",
      "14",
      "15",
      "16",
      "17",
      "18",
      "19",
      "21",
      "22",
      "23",
      "24",
      "25",
      "26",
      "27",
      "28",
      "29",
      "2A",
      "2B",
      "30",
      "31",
      "32",
      "33",
      "34",
      "35",
      "36",
      "37",
      "38",
      "39",
      "40",
      "41",
      "42",
      "43",
      "44",
      "45",
      "46",
      "47",
      "48",
      "49",
      "50",
      "51",
      "52",
      "53",
      "54",
      "55",
      "56",
      "57",
      "58",
      "59",
      "60",
      "61",
      "62",
      "63",
      "64",
      "65",
      "66",
      "67",
      "68",
      "69",
      "70",
      "71",
      "72",
      "73",
      "74",
      "75",
      "76",
      "77",
      "78",
      "79",
      "80",
      "81",
      "82",
      "83",
      "84",
      "85",
      "86",
      "87",
      "88",
      "89",
      "90",
      "91",
      "92",
      "93",
      "94",
      "95",
      "971",
      "972",
      "973",
      "974",
      "976"
  ];


    foreach ($morbihan as $key => $city) {
      $current = new City();
      $current->setName($city['name']);
      $current->setZipCode($city['zip_code']);
      $current->setDepartment($this->getReference('dep-' . $departments[array_rand($departments)]));
      $this->addReference('city-' . $key, $current);

      $manager->persist($current);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [
      DepartmentFixtures::class,
    ];
  }
}
