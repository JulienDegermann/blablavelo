<?php


namespace App\Domain\User\Contrat;

use App\Domain\User\User;

interface DeleteAccountInterface
{
    public function __invoke(User $user): string;
}