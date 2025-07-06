<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage;

use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;
use App\Infrastructure\Service\Messages\EmailMessages\RemoveRideEmailMessage\RemoveRidePublisherInterface;

final class RemoveRidePublisher extends SendEmailPublisherAbstract implements RemoveRidePublisherInterface
{
    public function getType(): string
    {
        return 'remove_ride';
    }
}
