<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Traits\Entity\DatesTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ModelRepository;
use App\Traits\Entity\NameNumberTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameNumberTrait;


    #[ORM\Column(nullable: true)]
    #[Assert\Sequentially([
        new Assert\Type(
            type: 'integer',
            message: 'Date invalide.'
        ),
        new Assert\Range(
            min: 1950,
            max: 'this year',
            notInRangeMessage: 'L\'année doit être comprise entre {{ value }} et l\'année courante.'
        ),
        new Assert\LessThanOrEqual(
            value: 'now',
            message: 'La date ne peut être postérieure à la date actuelle.'
        )
    ])]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'models')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Valid]
    private ?Brand $brand = null;

    #[ORM\OneToMany(mappedBy: 'bike', targetEntity: User::class)]
    #[Assert\Valid]
    private Collection $users;

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

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
            $user->setBike($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getBike() === $this) {
                $user->setBike(null);
            }
        }

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return strtoupper($this->brand->getNameNumber()) . ' ' . $this->nameNumber . ' ' . $this->year;
    }
}
