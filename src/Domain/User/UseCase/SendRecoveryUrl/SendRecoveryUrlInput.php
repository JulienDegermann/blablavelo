<?php

namespace App\Domain\User\UseCase\SendRecoveryUrl;

final class SendRecoveryUrlInput
{
    private string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}