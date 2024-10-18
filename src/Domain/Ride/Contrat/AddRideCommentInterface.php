<?php

namespace App\Domain\Ride\Contrat;

use App\Domain\Ride\Ride;
use App\Domain\Ride\UseCase\AddRideComment\AddRideCommentInput;
use App\Domain\User\User;

interface AddRideCommentInterface
{
    public function __invoke(AddRideCommentInput $input): Ride;
}