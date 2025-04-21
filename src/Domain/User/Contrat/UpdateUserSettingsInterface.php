<?php
namespace App\Domain\User\Contrat;
use App\Domain\User\UseCase\UpdateUserSettings\UpdateUserSettingsInput;
use App\Domain\User\User;

interface UpdateUserSettingsInterface
{
    public function __invoke(UpdateUserSettingsInput $input, User $user): string;
}