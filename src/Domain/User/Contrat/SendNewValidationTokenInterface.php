<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;
use Symfony\Component\Mime\Email;

interface SendNewValidationTokenInterface
{
    public function __invoke(User $user): Email;
}