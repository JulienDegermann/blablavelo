<?php

namespace App\Domain\Ride\UseCase;

use App\Domain\Ride\Ride;
use DateTimeImmutable;

final class RideOutput
{
    private Ride $ride;

    public function getData(): Ride
    {
        return $this->ride;
    }

    public function setData(Ride $ride): self
    {
        $this->ride = $ride;

        return $this;
    }
}