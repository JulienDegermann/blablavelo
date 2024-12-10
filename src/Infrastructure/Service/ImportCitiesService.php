<?php

namespace App\Infrastructure\Service;

use App\Domain\Location\City;
use App\Domain\Location\Department;
use App\Infrastructure\Repository\CityRepository;
use App\Infrastructure\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ImportCitiesService
{
	public function __construct(
		private CityRepository $cityRepo,
		private DepartmentRepository $departmentRepo,
		private EntityManagerInterface $em
	) {
	}

	public function importCities(SymfonyStyle $io,): void
	{
		$io->title('Importation des villes');
		// use CSV file
		$cities = $this->readCsvFile();
		$io->progressStart(count($cities));

		foreach ($cities as $arrayCity) {
			// save cities
			$department = $this->departmentRepo->findOneBy(['code' => $arrayCity['department_number']]);  // changer ici la valeur
			
			$city = $this->createOrUpdateCity($arrayCity, $department);
			$this->em->persist($city);

			$io->progressAdvance();
		}
		$this->em->flush();

		$io->progressFinish();
		$io->success('L\'importation est terminée');
	}

	public function importDepartments(SymfonyStyle $io): void
	{
		$io->title('Importation des départements');
		// use CSV file
		$cities = $this->readCsvFile();
		$io->progressStart(count($cities));

		foreach ($cities as $arrayCity) {
			// save departments
			$department = $this->createOrUpdateDepartment($arrayCity);
			$this->em->persist($department);
			$this->em->flush();

			$io->progressAdvance();
		}

		$io->progressFinish();
		$io->success('L\'importation est terminée');
	}

	private function readCsvFile(): Reader
	{
		// class Reader (librairy : composer require	league/csv / kernel = repertoire root du projet ; mode Read
		$csv = Reader::createFromPath('%kernel.root.dir%/../import/cities.csv', 'r'); 
		// header = ligne 0 du fichier
		$csv->setHeaderOffset(0); 
		
		return $csv;
	}


	private function createOrUpdateDepartment(array $arrayCity): Department
	{
		$department = $this->departmentRepo->findOneBy(['code' => $arrayCity['department_number']]);  // changer ici la valeur

		if (!$department) {
			$department = new Department();
			$department->setName($arrayCity['department_name'])
				->setCode($arrayCity['department_number']);
		}

		return $department;
	}

	private function createOrUpdateCity(array $arrayCity, Department $department): City
	{
		$city = $this->cityRepo->findOneBy(['insee_code' => $arrayCity['insee_code']]);
		if (!$city) {
			$city = new City();
		}

		$city->setName($arrayCity['label']) // trouver la bonne colonne
			->setZipCode($arrayCity['zip_code']) // trouver la bonne colonne
			->setInseeCode($arrayCity['insee_code']) // trouver la bonne colonne
			->setDepartment($department); // trouver la bonne colonne;

		return $city;
	}
}
