<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\UseCase\SendRecoveryUrl\SendRecoveryUrlInput;
use App\Domain\User\User;

interface SendRecoveryUrlInterface
{
    public function __invoke(SendRecoveryUrlInput $input): User;
}