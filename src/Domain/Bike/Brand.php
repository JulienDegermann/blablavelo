<?php

namespace App\Domain\Bike;

use App\Application\Traits\Entity\DatesTrait;
use App\Application\Traits\Entity\IdTrait;
use App\Application\Traits\Entity\NameNumberTrait;
use App\Infrastructure\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameNumberTrait;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Model::class, cascade: ['persist', 'remove'])]
    private Collection $models;

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): static
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
            $model->setBrand($this);
        }

        return $this;
    }

    public function removeModel(Model $model): static
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getBrand() === $this) {
                $model->setBrand(null);
            }
        }

        return $this;
    }
    
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->models = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nameNumber;
    }
}
