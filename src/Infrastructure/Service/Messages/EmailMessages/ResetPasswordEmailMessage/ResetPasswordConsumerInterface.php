<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage;

interface ResetPasswordConsumerInterface
{
    public function __invoke(
        int $user_id
    ): void;
}
