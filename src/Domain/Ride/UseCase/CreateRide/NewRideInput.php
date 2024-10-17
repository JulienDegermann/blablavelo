<?php

namespace App\Domain\Ride\UseCase\CreateRide;

use App\Application\Location\City;
use App\Domain\Ride\UseCase\Collection;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\User;
use DateTimeImmutable;

final class NewRideInput
{
    private string $title;

    private string $description;

    private int $ascent;

    private int $averageSpeed;

    private int $distance;

    private int $maxParticipants;

    private DateTimeImmutable $startDate;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    private Mind $mind;

    private Practice $practice;

    private User $creator;

    private Collection $participants;

    private City $startCity;

    private Collection $rideComments;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAscent(): int
    {
        return $this->ascent;
    }

    public function setAscent(int $ascent): self
    {
        $this->ascent = $ascent;

        return $this;
    }

    public function getAverageSpeed(): int
    {
        return $this->averageSpeed;
    }

    public function setAverageSpeed(int $averageSpeed): self
    {
        $this->averageSpeed = $averageSpeed;

        return $this;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMind(): Mind
    {
        return $this->mind;
    }

    public function setMind(Mind $mind): self
    {
        $this->mind = $mind;

        return $this;
    }

    public function getPractice(): Practice
    {
        return $this->practice;
    }

    public function setPractice(Practice $practice): self
    {
        $this->practice = $practice;

        return $this;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getSartCity(): City
    {
        return $this->startCity;
    }

    public function setSartCity(City $startCity): self
    {
        $this->startCity = $startCity;

        return $this;
    }

    public function getParticipants(): array
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            if (!$participant->getRides->contains($this)) {
                $participant->addRide($this);
            }
        }

        return $this;
    }
}
