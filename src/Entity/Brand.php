<?php

namespace App\Entity;

use App\Entity\Model;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\BrandRepository;
use App\Traits\Entity\NameNumberTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    // Traits calls
    use IdTrait;
    use DatesTrait;
    use NameNumberTrait;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Model::class)]
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
