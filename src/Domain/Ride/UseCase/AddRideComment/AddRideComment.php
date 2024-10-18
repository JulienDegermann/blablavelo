<?php

namespace App\Domain\Ride\UseCase\AddRideComment;

use App\Domain\Ride\Contrat\AddRideCommentInterface;
use App\Domain\Ride\Contrat\RideRepositoryInterface;
use App\Domain\Ride\Ride;
use App\Domain\Ride\RideComment;
use App\Domain\User\User;

final class AddRideComment implements AddRideCommentInterface
{
    public function __construct(private readonly RideRepositoryInterface $rideRepo)
    {
    }
    public function __invoke(AddRideCommentInput $input): Ride
    {
        $ride = $input->getRide();
        $comment = new RideComment();
        $comment
            ->setRide($ride)
            ->setAuthor($input->getAuthor())
            ->setText($input->getText());
        $ride->addRideComment($comment);

        $this->rideRepo->save($ride);

        return $ride;
    }
}