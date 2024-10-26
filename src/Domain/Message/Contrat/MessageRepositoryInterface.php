<?php

namespace App\Domain\Message\Contrat;

use App\Domain\Message\Message;
interface MessageRepositoryInterface
{
    public function save(Message $message): void;
}