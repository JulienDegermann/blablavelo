<?php

namespace App\Infrastructure\Service\Messages\EmailMessages\DeleteAccountEmailMessage;

use App\Infrastructure\Service\Messages\EmailMessages\SendEmailPublisherAbstract;

/**
 * Class DeleteAccountPublisher - queue datas for delete user account (send e-mail)
 */
final class DeleteAccountPublisher extends SendEmailPublisherAbstract implements DeleteAccountPublisherInterface
{
    protected function getType(): string
    {
        return 'delete_account';
    }
}
