<?php

namespace App\Entity;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Traits\IdTrait;
use App\Traits\Entity\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    // Traits calls
    use IdTrait;
    use NameTrait;
    use DatesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères'
        ),
        new Assert\Length(
            min: 5,
            max: 5,
            minMessage: 'Ce champ doit contenir 5 chiffres',
            maxMessage: 'Ce champ doit contenir 5 chiffres'
        ),
        new Assert\Regex(
            pattern: '/^[0-9]{5}$/',
            message: 'Ce champ doit contenir 5 chiffres'
        )
    ])]
    private ?int $zip_code = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Department $department = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: User::class)]
    #[Assert\Valid]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Ride::class)]
    #[Assert\Valid]
    private Collection $rides;

    #[ORM\Column(length: 10)]
    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères'
        ),
        new Assert\Length(
            min: 5,
            max: 5,
            minMessage: 'Ce champ doit contenir 5 chiffres',
            maxMessage: 'Ce champ doit contenir 5 chiffres'
        ),
        new Assert\Regex(
            pattern: '/^[0-9]{5}$/',
            message: 'Ce champ doit contenir 5 chiffres'
        )
    ])]
    private ?string $insee_code = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zip_code): static
    {
        $this->zip_code = $zip_code;

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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCity($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCity() === $this) {
                $user->setCity(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): static
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setCity($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCity() === $this) {
                $ride->setCity(null);
            }
        }

        return $this;
    }

    public function getInseeCode(): ?string
    {
        return $this->insee_code;
    }

    public function setInseeCode(string $insee_code): static
    {
        $this->insee_code = $insee_code;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }
}
