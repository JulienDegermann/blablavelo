<?php

namespace App\Domain\Ride\Contrat;

use Symfony\Component\Mime\Email;

interface RemoveRideInterface{
    public function __invoke(RemoveRideInput $input): Email;
}