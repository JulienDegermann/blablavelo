<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage;

interface DeleteAccountConsumerInterface
{
    public function __invoke(array $user_datas): void;
}
