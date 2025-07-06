<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;
use App\Domain\User\UseCase\CreateUser\CreateUserInput;

interface CreateUserInterface
{
    public function __invoke(CreateUserInput $input): User;
}
