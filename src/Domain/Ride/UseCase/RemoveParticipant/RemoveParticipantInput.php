<?php

namespace App\Domain\Ride\UseCase\RemoveParticipant;

use App\Domain\User\User;

final class RemoveParticipantInput
{
    private int $rideId;

    private User $participant;

    public function getParticipant(): User
    {
        return $this->participant;
    }

    public function getRideId(): int
    {
        return $this->rideId;
    }

    public function setRideId(int $rideId): self
    {
        $this->rideId = $rideId;

        return $this;
    }

    public function setParticipant(User $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}