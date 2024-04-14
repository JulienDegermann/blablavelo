<?php

namespace App\Entity;

use DateImmutable;
use App\Entity\Ride;
use DateTimeImmutable;
use App\Entity\Message;
use App\Entity\Traits\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\UserRepository;
use App\Traits\Entity\NameNumberTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['user_name', 'email'], message: 'Identifiants indisponibles..')]
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
    #[ORM\Column]
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
            pattern: '/^[a-zA-Z\-\p{L}]{2, 255}$/u',
            message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre.'
        )
    ])]
    private ?string $first_name = null;

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
            pattern: '/^[a-zA-Z\-\p{L}]{2, 255}$/u',
            message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule et un chiffre'
        )
    ])]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Assert\Sequentially([
        new Assert\DateTime(
            message: 'Date non valide.'
        ),
        new Assert\LessThanOrEqual(
            value: 'today - 18 years',
            message: 'Vous devez avoir au moins 18 ans pour vous inscrire.'
        )
    ])]
    private ?DateTimeImmutable $birth_date = null;

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
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\Valid]
    private ?Mind $mind = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Ride::class)]
    #[Assert\Valid]
    private Collection $rides_created;

    #[ORM\ManyToMany(mappedBy: 'participants', targetEntity: Ride::class)]
    #[Assert\Valid]
    private Collection $rides_participated;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class)]
    #[Assert\Valid]
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
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeImmutable $birth_date): static
    {
        $this->birth_date = $birth_date;

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
        return $this->rides_created;
    }

    public function addRidesCreated(Ride $rideCreated): static
    {
        if (!$this->rides_created->contains($rideCreated)) {
            $this->rides_created->add($rideCreated);
            $rideCreated->setAuthor($this);
        }

        return $this;
    }

    public function removeRidesCreated(Ride $rideCreated): static
    {
        if ($this->rides_created->removeElement($rideCreated)) {
            // set the owning side to null (unless already changed)
            if ($rideCreated->getAuthor() === $this) {
                $rideCreated->setAuthor(null);
            }
        }

        return $this;
    }


    public function getRidesParticipated(): Collection
    {
        return $this->rides_participated;
    }

    public function addRidesParticipated(Ride $rideParticipated): static
    {
        if (!$this->rides_participated->contains($rideParticipated)) {
            $this->rides_participated->add($rideParticipated);
            $rideParticipated->addParticipant($this);
        }

        return $this;
    }


    public function removeRidesParticipated(Ride $ridePartipated): static
    {
        if ($this->rides_participated->removeElement($ridePartipated)) {
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
        $this->rides_created = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAt(new DateTimeImmutable());
        $this->setIsVerified(false);
        $this->messages = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nameNumber;
    }
}
