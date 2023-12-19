<?php

namespace App\Entity;

use App\Repository\PracticeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PracticeRepository::class)]
class Practice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'practice', targetEntity: Ride::class)]
    private Collection $rides;

    #[ORM\OneToMany(mappedBy: 'practice', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $ride->setPractice($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getPractice() === $this) {
                $ride->setPractice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getusers(): Collection
    {
        return $this->users;
    }

    public function addusers(User $users): static
    {
        if (!$this->users->contains($users)) {
            $this->users->add($users);
            $users->setPractice($this);
        }

        return $this;
    }

    public function removeusers(User $users): static
    {
        if ($this->users->removeElement($users)) {
            // set the owning side to null (unless already changed)
            if ($users->getPractice() === $this) {
                $users->setPractice(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
