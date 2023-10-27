<?php

namespace App\Entity;

use App\Entity\Ride;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $user_name = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $birth_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_name = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mind $mind = null;

    #[ORM\OneToMany(mappedBy: 'user_creator', targetEntity: Ride::class)]
    private Collection $rides_created;
    
    // link ManyToMany 
    #[ORM\ManyToMany(mappedBy: 'user_participant', targetEntity: Ride::class)]
    private Collection $rides_participated;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Practice $practice = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Model $bike = null;



    public function __construct()
    {
        $this->rides_created = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(string $user_name): static
    {
        $this->user_name = $user_name;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->user_name;
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

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): static
    {
        $this->file_name = $file_name;

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

    public function addRidesCreated(Ride $ridesCreated): static
    {
        if (!$this->rides_created->contains($ridesCreated)) {
            $this->rides_created->add($ridesCreated);
            $ridesCreated->setUserCreator($this);
        }

        return $this;
    }

    public function removeRidesCreated(Ride $ridesCreated): static
    {
        if ($this->rides_created->removeElement($ridesCreated)) {
            // set the owning side to null (unless already changed)
            if ($ridesCreated->getUserCreator() === $this) {
                $ridesCreated->setUserCreator(null);
            }
        }

        return $this;
    }


    public function getRidesParticipated(): Collection
    {
        return $this->rides_participated;
    }

    public function addRidesParticipated(Ride $ridesParticipated): static
    {
        if (!$this->rides_participated->contains($ridesParticipated)) {
            $this->rides_participated->add($ridesParticipated);
            $ridesParticipated->setUserCreator($this);
        }

        return $this;
    }


    // add functions
    public function removeRidesParticipant(Ride $ridesPartipated): static
    {
        if ($this->rides_created->removeElement($ridesPartipated)) {
            // set the owning side to null (unless already changed)
            if ($ridesPartipated->getUserCreator() === $this) {
                $ridesPartipated->setUserCreator(null);
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


}
