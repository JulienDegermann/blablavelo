<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

class RemoveRideInput
{
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}