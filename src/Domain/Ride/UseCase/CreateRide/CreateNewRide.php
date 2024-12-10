<?php

namespace App\Domain\Ride\UseCase\CreateRide;

use App\Domain\Ride\Contrat\CreateNewRideInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;
use DateTimeImmutable;
use InvalidArgumentException;

final class CreateNewRide implements CreateNewRideInterface
{
    public function __construct(private readonly RideRepositoryInterface $rideRepo) {}

    public function __invoke(NewRideInput $input): Ride
    {
        if ($input->getStartDate() < new DateTimeImmutable()) {
            throw new InvalidArgumentException('Date de départ invalide. Elle doit être postérieure à la date du jour.');
        }

        if (($input->getMaxParticipants() < $x = 2) || ($input->getMaxParticipants() > $y = 10)) {
            throw new InvalidArgumentException("Nombre de participants invalide. Il doit être compris entre $x et $y.");
        }


        if (($input->getAscent() < $x = 0) || ($input->getAscent() > $y = 3000)) {
            throw new InvalidArgumentException("Dénivelé invalide. Il doit être compris entre $x et $y.");
        }

        if (($input->getDistance() < $x = 10) || ($input->getDistance() > $y = 200)) {
            throw new InvalidArgumentException("Distance invalide. Elle doit être comprise entre $x et $y.");
        }

        if (($input->getAverageSpeed() < $x = 5) || ($input->getAverageSpeed() > $y = 50)) {
            throw new InvalidArgumentException("Vitesse moyenne invalide. Elle doit être comprise entre $x et $y.");
        }


        $ride = new Ride(
            $input->getCreator(),
            $input->getTitle(),
            $input->getDescription(),
            $input->getStartDate(),
            $input->getMaxParticipants(),
            $input->getAscent(),
            $input->getDistance(),
            $input->getAverageSpeed(),
            $input->getPractice(),
            $input->getMind(),
            $input->getStartCity()
        );

        $this->rideRepo->save($ride);

        return $ride;
    }
}
