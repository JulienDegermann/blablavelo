<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage;

interface RemoveRidePublisherInterface
{
    public function getType(): string;
}
