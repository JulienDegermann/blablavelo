<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\UseCase\ResetPassword\ResetPasswordInput;
use App\Domain\User\User;

interface ResetPasswordInterface
{
    public function __invoke(ResetPasswordInput $input): User;
}