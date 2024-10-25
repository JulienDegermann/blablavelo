<?php

namespace App\Domain\User\UseCase\CreateUser;


final class CreateUserInput
{

    private string $email;

    private string $nameNumber;

    private string $password;

    private bool $RGPDConscents;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNameNumber(): string
    {
        return $this->nameNumber;
    }

    public function setNameNumber(string $nameNumber): self
    {
        $this->nameNumber = $nameNumber;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRGPDConscents(): bool
    {
        return $this->RGPDConscents;
    }

    public function setRGPDConscents(bool $RGPDConscents): self
    {
        $this->RGPDConscents = $RGPDConscents;

        return $this;
    }

    public function __construct()
    {
        $this->RGPDConscents = false;
    }
}
