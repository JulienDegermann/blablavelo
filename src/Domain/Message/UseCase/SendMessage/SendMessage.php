<?php

namespace App\Domain\Message\UseCase\SendMessage;

use App\Domain\Message\Contrat\SendMessageInterface;
use App\Domain\Message\Message;
use App\Domain\User\User;

final class SendMessage implements SendMessageInterface
{
    public function __construct()
    {
    }

    public function __invoke(SendMessageInput $input): void
    {
        $message = new Message(
            $input->getTitle(),
            $input->getText(),

        );
    }
}
