<?php

namespace App\Domain\Ride\UseCase;

use App\Domain\Ride\Contrat\CreateNewRideInterface;
use App\Infrastructure\Repository\RideRepository;

final class CreateNewRide implements CreateNewRideInterface
{
    public function __construct(private readonly RideRepository $rideRepo) {}
    public function __invoke(NewRideInput $input): RideOutput
    {
        $output = new RideOutput();

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
        );

        $this->RideRepo->save($ride);

        return $output->setData($ride);
    }
}