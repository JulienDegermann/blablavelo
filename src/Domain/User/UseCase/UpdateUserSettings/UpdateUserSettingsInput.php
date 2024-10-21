<?php

namespace App\Domain\User\UseCase\UpdateUserSettings;

use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\User;

final class UpdateUserSettingsInput
{
    private string $email;

    private ?Mind $mind = null;

    private ?Practice $practice = null;

    private ?Department $department = null;

    public function getMind(): ?Mind
    {
        return $this->mind;
    }

    public function setMind(Mind $mind): self
    {
        $this->mind = $mind;

        return $this;
    }

    public function getPractice(): ?Practice
    {
        return $this->practice;
    }

    public function setPractice(Practice $practice): self
    {
        $this->practice = $practice;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}