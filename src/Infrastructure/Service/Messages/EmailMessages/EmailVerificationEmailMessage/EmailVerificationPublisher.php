<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage;


use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;
use App\Infrastructure\Service\Messages\EmailMessages\EmailVerificationEmailMessage\EmailVerificationPublisherInterface;


/**
 * Class EmailVerificationPublisher - queue datas for email verification (send e-mail) 
 */
final class EmailVerificationPublisher extends SendEmailPublisherAbstract implements EmailVerificationPublisherInterface
{

    protected function getType(): string
    {
        return  "email_verification";
    }
}
