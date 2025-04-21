<?php

namespace App\Domain\User\UseCase\ResetPassword;

use App\Domain\User\User;

final class ResetPasswordInput
{
    private string $password;

    private User $user;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
