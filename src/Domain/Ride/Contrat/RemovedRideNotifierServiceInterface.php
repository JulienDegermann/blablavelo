<?php

namespace App\Domain\Ride\Contrat;
use App\Domain\Ride\Ride;
use App\Domain\User\User;
use Symfony\Component\Mime\Email;

interface RemovedRideNotifierServiceInterface
{
    public function __invoke(User $participant, Ride $ride): Email;
}