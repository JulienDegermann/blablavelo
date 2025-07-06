<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;
use Symfony\Component\Mime\Email;

interface EmailVerificationNotifierServiceInterface
{
    public function __invoke(User $user): Email;
}