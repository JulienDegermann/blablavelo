<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Repository\RideCommentRepository;
use App\Traits\Entity\DatesTrait;
use App\Traits\Entity\TextTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RideCommentRepository::class)]
class RideComment
{

    use DatesTrait;
    use IdTrait;
    use TextTrait;

    #[ORM\ManyToOne(inversedBy: 'rideComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'rideComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ride $ride = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getRide(): ?Ride
    {
        return $this->ride;
    }

    public function setRide(?Ride $ride): static
    {
        $this->ride = $ride;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->text;
    }
}
