<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage;

use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;
use App\Infrastructure\Service\Messages\EmailMessages\ResetPasswordEmailMessage\ResetPasswordPublisherInterface;

/**
 * Class ResetPasswordPublisher - queue datas for password recovery (send e-mail) 
 */
final class ResetPasswordPublisher extends SendEmailPublisherAbstract implements ResetPasswordPublisherInterface
{


    protected function getType(): string
    {
        return  "reset_password";
    }
}
