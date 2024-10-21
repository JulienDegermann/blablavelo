<?php

namespace App\Domain\Message\Contrat;

use App\Domain\Message\UseCase\SendMessage\SendMessageInput;
use App\Domain\User\User;

interface SendMessageInterface
{
    public function __invoke(SendMessageInput $input): void;
}