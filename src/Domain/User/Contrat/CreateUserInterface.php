<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\UseCase\CreateUser\CreateUserInput;
use App\Domain\User\User;

interface CreateUserInterface
{
    public function __invoke(CreateUserInput $input): User;
}