<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Traits\Entity\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\PracticeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PracticeRepository::class)]
class Practice
{
    // Traits calls
    use IdTrait;
    use NameTrait;
    use DatesTrait;

    #[ORM\OneToMany(mappedBy: 'practice', targetEntity: Ride::class)]
    #[Assert\Valid]
    private Collection $rides;

    #[ORM\OneToMany(mappedBy: 'practice', targetEntity: User::class)]
    #[Assert\Valid]
    private Collection $users;

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

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
        $this->rides = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
}
