<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Mind;
use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\Practice;
use App\Entity\Department;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $departments = [
            "01" => "Ain",
            "02" => "Aisne",
            "03" => "Allier",
            "04" => "Alpes-de-Haute-Provence",
            "05" => "Hautes-Alpes",
            "06" => "Alpes-Maritimes",
            "07" => "Ardèche",
            "08" => "Ardennes",
            "09" => "Ariège",
            "10" => "Aube",
            "11" => "Aude",
            "12" => "Aveyron",
            "13" => "Bouches-du-Rhône",
            "14" => "Calvados",
            "15" => "Cantal",
            "16" => "Charente",
            "17" => "Charente-Maritime",
            "18" => "Cher",
            "19" => "Corrèze",
            "21" => "Côte-d'Or",
            "22" => "Côtes-d'Armor",
            "23" => "Creuse",
            "24" => "Dordogne",
            "25" => "Doubs",
            "26" => "Drôme",
            "27" => "Eure",
            "28" => "Eure-et-Loir",
            "29" => "Finistère",
            "2A" => "Corse-du-Sud",
            "2B" => "Haute-Corse",
            "30" => "Gard",
            "31" => "Haute-Garonne",
            "32" => "Gers",
            "33" => "Gironde",
            "34" => "Hérault",
            "35" => "Ille-et-Vilaine",
            "36" => "Indre",
            "37" => "Indre-et-Loire",
            "38" => "Isère",
            "39" => "Jura",
            "40" => "Landes",
            "41" => "Loir-et-Cher",
            "42" => "Loire",
            "43" => "Haute-Loire",
            "44" => "Loire-Atlantique",
            "45" => "Loiret",
            "46" => "Lot",
            "47" => "Lot-et-Garonne",
            "48" => "Lozère",
            "49" => "Maine-et-Loire",
            "50" => "Manche",
            "51" => "Marne",
            "52" => "Haute-Marne",
            "53" => "Mayenne",
            "54" => "Meurthe-et-Moselle",
            "55" => "Meuse",
            "56" => "Morbihan",
            "57" => "Moselle",
            "58" => "Nièvre",
            "59" => "Nord",
            "60" => "Oise",
            "61" => "Orne",
            "62" => "Pas-de-Calais",
            "63" => "Puy-de-Dôme",
            "64" => "Pyrénées-Atlantiques",
            "65" => "Hautes-Pyrénées",
            "66" => "Pyrénées-Orientales",
            "67" => "Bas-Rhin",
            "68" => "Haut-Rhin",
            "69" => "Rhône",
            "70" => "Haute-Saône",
            "71" => "Saône-et-Loire",
            "72" => "Sarthe",
            "73" => "Savoie",
            "74" => "Haute-Savoie",
            "75" => "Paris",
            "76" => "Seine-Maritime",
            "77" => "Seine-et-Marne",
            "78" => "Yvelines",
            "79" => "Deux-Sèvres",
            "80" => "Somme",
            "81" => "Tarn",
            "82" => "Tarn-et-Garonne",
            "83" => "Var",
            "84" => "Vaucluse",
            "85" => "Vendée",
            "86" => "Vienne",
            "87" => "Haute-Vienne",
            "88" => "Vosges",
            "89" => "Yonne",
            "90" => "Territoire de Belfort",
            "91" => "Essonne",
            "92" => "Hauts-de-Seine",
            "93" => "Seine-Saint-Denis",
            "94" => "Val-de-Marne",
            "95" => "Val-d'Oise",
            "971" => "Guadeloupe",
            "972" => "Martinique",
            "973" => "Guyane",
            "974" => "La Réunion",
            "976" => "Mayotte"
        ];

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

        $bikeModels = [
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
        
        $minds = [
            'Ballade',
            'Sport : Entretien',
            'Sport : Compétition'
        ];

        $practices = [
            'VTT roulant',
            'VTT engagé',
            'Gravel',
            'Route',
            'Bike Packing',
        ];

        foreach($departments as $key => $department) {
            $dep = new Department();
            $dep->setCode($key);
            $dep->setName($department);
            $manager->persist($dep);
        }
        $manager->flush();

        foreach($morbihan as $city) {
            $cit = new City();
            $cit->setZipCode($city['zip_code']);
            $cit->setName($city['name']);

            // get department morbihan
            $morbihanDepartment = $manager->getRepository(Department::class)->findOneBy(['name' => 'Morbihan']);
            $cit->setDepartment($morbihanDepartment);

            $manager->persist($cit);
        }

        foreach($bikeModels as $brands => $models) {
            $brand = new Brand();
            $brand->setName($brands);
            $manager->persist($brand);
        }
        $manager->flush();
        foreach($bikeModels as $brands => $models) {
            foreach($models as $modelItem) {
                $model = new Model();
                $model->setName($modelItem);
                $dateString = new \DateTimeImmutable();
                $model->setYear($dateString);
                $brand = $manager->getRepository(Brand::class)->findOneBy(['name' => $brands]);
                $model->setBrand($brand);
                $manager->persist($model);
            }
        }

        foreach($minds as $mindItem) {
            $mind = new Mind();
            $mind->setName($mindItem);
            $manager->persist($mind);
        }

        foreach($practices as $practiceItem) {
            $practice = new Practice();
            $practice->setName($practiceItem);
            $manager->persist($practice);
        }
        $manager->flush();
    }
}
