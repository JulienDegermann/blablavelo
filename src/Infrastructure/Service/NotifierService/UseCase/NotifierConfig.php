<?php

namespace App\Infrastructure\Service\NotifierService\UseCase;

use App\Infrastructure\Service\NotifierService\NotifierConfigInterface;

class NotifierConfig implements NotifierConfigInterface
{
    public function getFrom(): string
    {
        return 'env(MAIL_FROM_ADDRESS)';
    }

    public function getSignature(): string
    {
        return "L'équipe de Blabla Vélo.";
    }
}