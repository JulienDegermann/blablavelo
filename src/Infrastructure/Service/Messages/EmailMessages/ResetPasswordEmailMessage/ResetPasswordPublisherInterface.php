<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage;

interface ResetPasswordPublisherInterface
{
    public function __invoke(int $id): void;
}
