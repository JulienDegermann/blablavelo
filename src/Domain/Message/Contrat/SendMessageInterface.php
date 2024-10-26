<?php

namespace App\Domain\Message\Contrat;

use App\Domain\Message\Message;
use App\Domain\Message\UseCase\SendMessage\SendMessageInput;

interface SendMessageInterface
{
    public function __invoke(SendMessageInput $input): Message;
}