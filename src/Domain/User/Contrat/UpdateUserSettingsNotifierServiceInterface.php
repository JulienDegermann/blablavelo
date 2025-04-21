<?php

namespace App\Domain\User\Contrat;

use App\Domain\User\User;
use Symfony\Component\Mime\Email;

interface UpdateUserSettingsNotifierServiceInterface
{
    public function notify(User $user): Email;
}