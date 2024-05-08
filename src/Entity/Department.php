<?php

namespace App\Entity;

use App\Traits\Entity\IdTrait;
use App\Traits\Entity\DatesTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepartmentRepository;
use App\Traits\Entity\NameTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameTrait;

    #[ORM\Column(length: 3)]
    #[Assert\Sequentially([
        new Assert\NotBlank(),
        new Assert\Type(
            type: 'string',
            message: 'Ce champ doit être une chaîne de caractères'
        ),
        new Assert\Length(
            min: 2,
            max: 4,
            minMessage: 'Ce champ doit contenir 5 chiffres',
            maxMessage: 'Ce champ doit contenir 5 chiffres'
        ),
        new Assert\Regex(
            pattern: '/^[0-9]{2,3}[A-B]{0,1}$/',
            message: 'Ce champ doit contenir 5 chiffres'
        )
    ])]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: City::class)]
    private Collection $cities;
    
    #[ORM\OneToMany(mappedBy: 'department', targetEntity: User::class)]
    private Collection $users;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->setDepartment($this);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getDepartment() === $this) {
                $city->setDepartment(null);
            }
        }

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
            $user->setDepartment($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDepartment() === $this) {
                $user->setDepartment(null);
            }
        }

        return $this;
    }
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->cities = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return   $this->code . ' - ' . strtoupper($this->name);
    }
}
