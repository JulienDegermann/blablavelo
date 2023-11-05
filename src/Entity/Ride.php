<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use App\Entity\Practice;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RideRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\VarDumper\Cloner\Data;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $distance = null;

    #[ORM\Column]
    private ?int $ascent = null;

    #[ORM\Column]
    private ?int $max_rider = null;

    #[ORM\Column]
    private ?int $average_speed = null;

    #[ORM\Column]
    private ?\DateTime $date = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mind $mind = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Practice $practice = null;

    #[ORM\ManyToOne(inversedBy: 'rides_created')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_creator = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'rides_participated')]
    #[ORM\JoinColumn(nullable: false)]
    private Collection $user_participant;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function __construct()
    {
        $this->user_participant = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getAscent(): ?int
    {
        return $this->ascent;
    }

    public function setAscent(int $ascent): static
    {
        $this->ascent = $ascent;

        return $this;
    }

    public function getMaxRider(): ?int
    {
        return $this->max_rider;
    }

    public function setMaxRider(int $max_rider): static
    {
        $this->max_rider = $max_rider;

        return $this;
    }

    public function getAverageSpeed(): ?int
    {
        return $this->average_speed;
    }

    public function setAverageSpeed(int $average_speed): static
    {
        $this->average_speed = $average_speed;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMind(): ?Mind
    {
        return $this->mind;
    }

    public function setMind(?Mind $mind): static
    {
        $this->mind = $mind;

        return $this;
    }

    public function getPractice(): ?Practice
    {
        return $this->practice;
    }

    public function setPractice(?Practice $practice): static
    {
        $this->practice = $practice;

        return $this;
    }

    public function getUserCreator(): ?User
    {
        return $this->user_creator;
    }

    public function setUserCreator(?User $user_creator): static
    {
        $this->user_creator = $user_creator;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserParticipant(): Collection
    {
        return $this->user_participant;
    }

    public function addUserParticipant(User $userParticipant): static
    {
        if (!$this->user_participant->contains($userParticipant)) {
            $this->user_participant->add($userParticipant);
        }

        return $this;
    }

    public function removeUserParticipant(User $userParticipant): static
    {
        $this->user_participant->removeElement($userParticipant);

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
