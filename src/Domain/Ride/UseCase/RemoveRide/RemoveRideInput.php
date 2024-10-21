<?php

namespace App\Domain\Ride\UseCase\RemoveRide;

use App\Domain\User\User;

class RemoveRideInput
{
    private int $id;

    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function __construct(int $id, User $user)
    {
        $this->id = $id;
        $this->user = $user;
    }
}