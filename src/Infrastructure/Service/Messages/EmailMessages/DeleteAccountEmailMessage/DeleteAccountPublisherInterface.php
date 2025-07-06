<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage;

interface DeleteAccountPublisherInterface
{
    public function __invoke(int $id): void;
}
