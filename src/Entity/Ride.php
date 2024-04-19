<?php

namespace App\Entity;

use App\Entity\Practice;
use App\Entity\Traits\IdTrait;
use App\Traits\Entity\DatesTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RideRepository;
use App\Traits\Entity\TitleTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use TitleTrait;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'integer',
            message: 'Ce champ doit être un nombre entier.'
        ),
        new Assert\Positive(
            message: 'La distance doit être supérieure à 0 kms.'
        )
    ])]
    private ?int $distance = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'integer',
            message: 'Ce champ doit être un nombre entier.'
        ),
        new Assert\PositiveOrZero(
            message: 'Le dénivelé positif doit être un nombre supérieur ou égal à 0 m.'
        ),
        new Assert\GreaterThanOrEqual(
            value: 0,
            message: 'Le dénivelé positif doit être supérieur ou égal à {{ compared_value }} m.'
        ),
        new Assert\LessThanOrEqual(
            value: 5000,
            message: 'Le dénivelé positif doit être indérieur ou égal à {{ compared_value }} m.'
        )
    ])]
    private ?int $ascent = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'integer',
            message: 'Ce champ doit être un nombre entier.'
        ),
        new Assert\Positive(
            message: 'Le nombre de participants doit être supérieur à 0.'
        ),
        new Assert\Range(
            min: 1,
            max: 10,
            notInRangeMessage: 'Le nombre de participants doit être compris entre {{ min }} et {{ max }}.'
        )
    ])]
    private ?int $max_rider = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'integer',
            message: 'Ce champ doit être un nombre entier.'
        ),
        new Assert\Positive(
            message: 'La vitesse moyenne doit être supérieure à 0 km/h.'
        ),
        new Assert\GreaterThanOrEqual(
            value: 0,
            message: 'La vitesse moyenne doit être supérieure à {{ compared_value }} km/h.'
        ),
        new Assert\LessThanOrEqual(
            value: 50,
            message: 'La vitesse moyenne doit être inférieure à {{ compared_value }} km/h.'
        )
    ])]
    private ?int $average_speed = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'datetimeimmutable',
            message: 'Ce champ doit être une date.'
        ),
        new Assert\GreaterThan(
            value: 'now',
            message: 'La date doit être postérieure à la date du jour.'
        )
    ])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Mind $mind = null;

    #[ORM\ManyToOne(inversedBy: 'rides')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Practice $practice = null;

    #[ORM\ManyToOne(inversedBy: 'ridesCreated')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Assert\Valid]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ridesParticipated')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\Valid]
    private Collection $participants;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaine de caractères.'
        ),
        new Assert\Length(
            min: 2,
            minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.'
        ),
        new Assert\Regex(
            pattern: '/^[a-zA-Z0-9\s()\-\'?:.,!\/\"\p{L}]{2,255}$/u',
            message: 'Les caractères autorisés : lettres (minuscules et majuscules, accentuées ou non), chiffres, espaces, parenthèses, tirets et caractères de ponctuation'
        )
    ])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?City $city = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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
}
