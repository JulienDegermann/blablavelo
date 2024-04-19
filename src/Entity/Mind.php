<?php

namespace App\Entity;

use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Traits\IdTrait;
use App\Traits\Entity\NameTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\MindRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MindRepository::class)]
class Mind
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameTrait;

    #[ORM\OneToMany(mappedBy: 'mind', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'mind', targetEntity: Ride::class)]
    #[Assert\Valid]
    private Collection $rides;


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
            $user->setMind($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getMind() === $this) {
                $user->setMind(null);
            }
        }

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
            $ride->setMind($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getMind() === $this) {
                $ride->setMind(null);
            }
        }

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->users = new ArrayCollection();
        $this->rides = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }
}
