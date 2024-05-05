<?php

namespace App\Entity;

use App\Entity\Ride;
use DateTimeImmutable;
use App\Entity\Message;
use App\Entity\ProfileImage;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\UserRepository;
use App\Traits\Entity\NameNumberTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: 'nameNumber', message: 'Identifiants indisponibles.')]
#[UniqueEntity(fields: 'email', message: 'Identifiants indisponibles.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameNumberTrait;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères.'
        ),
        new Assert\Length(
            min: 12,
            max: 255,
            minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Le mot de passe ne peut pas dépasser {{ limit }} caractères.'
        ),
        new Assert\Regex(
            pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,}$/',
            message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.'
        )
    ])]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères.'
        ),
        new Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Ce champ ne peut pas dépasser {{ limit }} caractères.'
        ),
        new Assert\Regex(
            pattern: '/^(?![×Þß÷þø])[a-zA-ZÀ-ÿ]{2,255}$/u',
            message: 'Ce champ contient des caractères non autorisés.'
        )
    ])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères.'
        ),
        new Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Ce champ ne peut pas dépasser {{ limit }} caractères.'
        ),
        new Assert\Regex(
            pattern: '/^(?![×Þß÷þø])[a-zA-ZÀ-ÿ]{2,255}$/u',
            message: 'Ce champ contient des caractères non autorisés.'
        )
    ])]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'datetimeimmutable',
            message: 'Date invalide.'
        ),
        new Assert\LessThanOrEqual(
            value: 'now',
            message: 'La date ne peut être postérieure à la date actuelle.'
        )
    ])]
    private ?DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\Sequentially([
        new Assert\NotBlank(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères.'
        ),
        new Assert\Email(
            message: 'Email non valide.'
        ),
        new Assert\Length(
            min: 6,
            max: 255,
            minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Ce champ ne peut pas dépasser {{ limit }} caractères.'
        ),
        new Assert\Regex(
            pattern: '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-]+)*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)$/',
            message: 'Email non valide.'
        )
    ])]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Valid]
    private ?Mind $mind = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Ride::class)]
    private Collection $ridesCreated;

    #[ORM\ManyToMany(mappedBy: 'participants', targetEntity: Ride::class)]
    private Collection $ridesParticipated;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class, cascade: ['persist', 'remove'])]
    private Collection $messages;

    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Valid]
    private ?Practice $practice = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Valid]
    private ?Model $bike = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères.'
        ),
        new Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
            maxMessage: 'Ce champ ne peut dépasser {{ limit }} caractères.'
        )
    ])]
    private ?string $token = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotNull(
            message: 'Ce champ est obligatoire.'
        ),
        new Assert\Type(
            type: 'bool',
            message: 'Ce champ doit être un booléen.'
        )
    ])]
    private ?bool $is_verified = false;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Valid]
    private ?Department $department = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ProfileImage $profileImage = null;

    /**
     * @var Collection<int, RideComment>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: RideComment::class)]
    private Collection $rideComments;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->nameNumber;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

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

    /**
     * @return Collection<int, Ride>
     */
    public function getRidesCreated(): Collection
    {
        return $this->ridesCreated;
    }

    public function addRidesCreated(Ride $rideCreated): static
    {
        if (!$this->ridesCreated->contains($rideCreated)) {
            $this->ridesCreated->add($rideCreated);
            $rideCreated->setAuthor($this);
        }

        return $this;
    }

    public function removeRidesCreated(Ride $rideCreated): static
    {
        if ($this->ridesCreated->removeElement($rideCreated)) {
            // set the owning side to null (unless already changed)
            if ($rideCreated->getAuthor() === $this) {
                $rideCreated->setAuthor(null);
            }
        }

        return $this;
    }


    public function getRidesParticipated(): Collection
    {
        return $this->ridesParticipated;
    }

    public function addRidesParticipated(Ride $rideParticipated): static
    {
        if (!$this->ridesParticipated->contains($rideParticipated)) {
            $this->ridesParticipated->add($rideParticipated);
            $rideParticipated->addParticipant($this);
        }

        return $this;
    }


    public function removeRidesParticipated(Ride $ridePartipated): static
    {
        if ($this->ridesParticipated->removeElement($ridePartipated)) {
            // set the owning side to null (unless already changed)

            if ($ridePartipated->getParticipants()->contains($this)) {
                $ridePartipated->removeParticipant($this);
            }
        }

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

    public function getBike(): ?Model
    {
        return $this->bike;
    }

    public function setBike(?Model $bike): static
    {
        $this->bike = $bike;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }



    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function __construct()
    {
        $this->ridesCreated = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->setIsVerified(false);
        $this->messages = new ArrayCollection();
        $this->rideComments = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nameNumber;
    }

    public function getProfileImage(): ?ProfileImage
    {
        return $this->profileImage;
    }

    public function setProfileImage(?ProfileImage $profileImage): static
    {
        $this->profileImage = $profileImage;

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
            $rideComment->setAuthor($this);
        }

        return $this;
    }

    public function removeRideComment(RideComment $rideComment): static
    {
        if ($this->rideComments->removeElement($rideComment)) {
            // set the owning side to null (unless already changed)
            if ($rideComment->getAuthor() === $this) {
                $rideComment->setAuthor(null);
            }
        }

        return $this;
    }
}
