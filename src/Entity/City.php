<?php

namespace App\Entity;

use App\Entity\Ride;
use App\Entity\User;
use App\Traits\Entity\IdTrait;
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
    private ?string $zip_code = null;

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Department $department = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Ride::class)]
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

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
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

    public function __toString()
    {
        return strtoupper($this->name) . ' (' . $this->zip_code . ')';
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }
}
