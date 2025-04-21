<?php

namespace App\Domain\Ride;

use App\Application\Traits\Entity\DatesTrait;
use App\Application\Traits\Entity\IdTrait;
use App\Application\Traits\Entity\TitleTrait;
use App\Domain\Location\City;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\User;
use App\Infrastructure\Repository\RideRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use TitleTrait;

    #[ORM\Column]
    private ?int $distance;

    #[ORM\Column]
    private ?int $ascent;

    #[ORM\Column]
    private ?int $maxParticipants;

    #[ORM\Column]
    private ?int $averageSpeed = null;

    #[ORM\Column]
    private ?DateTimeImmutable $startDate;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Mind $mind;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Practice $practice;

    #[ORM\ManyToOne(inversedBy: 'ridesCreated')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    #[Assert\Valid]
    private User $creator;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ridesParticipated')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\Valid]
    private Collection $participants;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?City $startCity;

    /**
     * @var Collection<int, RideComment>
     */
    #[ORM\OneToMany(mappedBy: 'ride', targetEntity: RideComment::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $rideComments;

    public function __construct(
        User              $creator,
        string            $title,
        ?string           $description = null,
        DateTimeImmutable $startDate,
        int               $maxParticipants,
        int               $ascent,
        int               $distance,
        int               $averageSpeed,
        Practice          $practice,
        Mind              $mind,
        City              $startCity,
    )
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->participants = new ArrayCollection();
        $this->rideComments = new ArrayCollection();
        $this->creator = $creator;
        $this->addParticipant($creator);
        $this->startDate = $startDate;
        $this->description = $description;
        $this->maxParticipants = $maxParticipants;
        $this->title = $title;
        $this->ascent = $ascent;
        $this->distance = $distance;
        $this->averageSpeed = $averageSpeed;
        $this->practice = $practice;
        $this->mind = $mind;
        $this->startCity = $startCity;
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
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): static
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getAverageSpeed(): ?int
    {
        return $this->averageSpeed;
    }

    public function setAverageSpeed(int $averageSpeed): static
    {
        $this->averageSpeed = $averageSpeed;

        return $this;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

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

    public function getcreator(): User
    {
        return $this->creator;
    }

    public function setcreator(User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getStartCity(): ?City
    {
        return $this->startCity;
    }

    public function setStartCity(?City $startCity): static
    {
        $this->startCity = $startCity;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
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

    /**
     * @return Collection<int, RideComment>
     */
    public function getRideComments(): Collection
    {
        return $this->rideComments;
    }

    public function addRideComment(RideComment $rideComment): static
    {
        if (!$this->rideComments->contains($rideComment)) {
            $this->rideComments->add($rideComment);
            $rideComment->setRide($this);
        }

        return $this;
    }

    public function removeRideComment(RideComment $rideComment): static
    {
        if ($this->rideComments->removeElement($rideComment)) {
            // set the owning side to null (unless already changed)
            if ($rideComment->getRide() === $this) {
                $rideComment->setRide(null);
            }
        }

        return $this;
    }
}
