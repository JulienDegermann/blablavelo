<?php

namespace App\Domain\Ride\UseCase\AddParticipant;

use App\Domain\User\User;

final class AddParticipantInput
{
    private int $rideId;

    private User $participant;

    public function getRideId(): int
    {
        return $this->rideId;
    }

    public function setRideId(int $rideId): self
    {
        $this->rideId = $rideId;

        return $this;
    }

    public function getParticipant(): User
    {
        return $this->participant;
    }

    public function setParticipant(User $participant): self
    {
        $this->participant = $participant;

        return $this;
    }

    public function __construct(int $rideId, User $participant){
        $this->rideId = $rideId;
        $this->participant = $participant;
    }
}