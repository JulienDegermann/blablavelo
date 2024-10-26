<?php

namespace App\Domain\Message\UseCase\SendMessage;

use App\Domain\Message\Contrat\MessageRepositoryInterface;
use App\Domain\Message\Contrat\SendMessageInterface;
use App\Domain\Message\Message;
use App\Domain\User\User;

final class SendMessage implements SendMessageInterface
{
    public function __construct(
        private readonly MessageRepositoryInterface $messageRepo
    )
    {
    }

    public function __invoke(SendMessageInput $input): Message
    {
        $message = new Message(
            $input->getTitle(),
            $input->getText(),
            $input->getUser(),
        );

        $this->messageRepo->save($message);

        return $message;


    }
}
