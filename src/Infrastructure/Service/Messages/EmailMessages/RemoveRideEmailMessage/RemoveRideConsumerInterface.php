<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage;

interface RemoveRideConsumerInterface
{
    public function __invoke(
        int $ride_id,
    ): void;
}
