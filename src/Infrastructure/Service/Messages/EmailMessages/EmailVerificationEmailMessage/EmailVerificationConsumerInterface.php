<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface EmailVerificationConsumerInterface
{
    public function __invoke(int $user_id): void;
}
