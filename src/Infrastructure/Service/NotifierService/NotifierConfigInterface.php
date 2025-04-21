<?php

namespace App\Infrastructure\Service\NotifierService;
interface NotifierConfigInterface
{
    public function getSignature(): string;

    public function getFrom(): string;
}