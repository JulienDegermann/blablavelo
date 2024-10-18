<?php

namespace App\Domain\Ride\UseCase\FindRides;

use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\User;
use DateTimeImmutable;

final class FindRidesInput
{
    private ?Practice $practice = null;

    private ?Mind $mind = null;

    private ?Department $department = null;

    private ?DateTimeImmutable $startDate;

    private ?int $minDistance = null;

    private ?int $maxDistance = null;

    private ?int $minParticipants = null;

    private ?int $maxParticipants = null;

    private ?int $minAscent = null;

    private ?int $maxAscent = null;

    private ?int $minAverageSpeed = null;

    private ?int $maxAverageSpeed = null;

    public function setPractice(?Practice $practice): self
    {
        $this->practice = $practice;

        return $this;
    }

    public function setMind(?Mind $mind): self
    {
        $this->mind = $mind;

        return $this;
    }

    public function getPractice(): ?Practice
    {
        return $this->practice;
    }

    public function getMind(): ?Mind
    {
        return $this->mind;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setStartDate(?DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setMinDistance(?int $minDistance): self
    {
        $this->minDistance = $minDistance;

        return $this;
    }

    public function getMinDistance(): ?int
    {
        return $this->minDistance;
    }

    public function getMaxDistance(): ?int
    {
        return $this->maxDistance;
    }

    public function setMaxDistance(?int $maxDistance): self
    {
        $this->maxDistance = $maxDistance;

        return $this;
    }

    public function getMinParticipants(): ?int
    {
        return $this->minParticipants;
    }

    public function setMinParticipants(?int $minParticipants): self
    {
        $this->minParticipants = $minParticipants;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(?int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getMinAscent(): ?int
    {
        return $this->minAscent;
    }

    public function setMinAscent(?int $minAscent): self
    {
        $this->minAscent = $minAscent;

        return $this;
    }

    public function getMaxAscent(): ?int
    {
        return $this->maxAscent;
    }

    public function setMaxAscent(?int $maxAscent): self
    {
        $this->maxAscent = $maxAscent;

        return $this;
    }

    public function getMinAverageSpeed(): ?int
    {
        return $this->minAverageSpeed;
    }

    public function setMinAverageSpeed(?int $minAverageSpeed): self
    {
        $this->minAverageSpeed = $minAverageSpeed;

        return $this;
    }

    public function getMaxAverageSpeed(): ?int
    {
        return $this->maxAverageSpeed;
    }

    public function setMaxAverageSpeed(?int $maxAverageSpeed): self
    {
        $this->maxAverageSpeed = $maxAverageSpeed;

        return $this;
    }

    public function __construct(User $user)
    {
        $this->startDate = new DateTimeImmutable();
        $this->minDistance = 15;
        $this->maxDistance = 60;
        $this->minParticipants = 4;
        $this->maxParticipants = 8;
        $this->minAscent = 200;
        $this->maxAscent = 2000;
        $this->mind = $user->getMind() ? $user->getMind() : null;
        $this->practice = $user->getPractice() ? $user->getPractice() : null;
        $this->department = $user->getDepartment() ? $user->getDepartment() : null;

    }
}