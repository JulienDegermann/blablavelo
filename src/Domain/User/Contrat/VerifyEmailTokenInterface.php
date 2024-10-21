<?php

namespace App\Domain\User\Contrat;
interface VerifyEmailTokenInterface
{
    public function __invoke(string $token): bool;
}