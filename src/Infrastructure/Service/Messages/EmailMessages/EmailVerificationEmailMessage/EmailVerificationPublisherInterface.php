<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage;


interface EmailVerificationPublisherInterface
{
    public function __invoke(int $id): void;
}
