<?php

namespace App\Application\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{

  #[ORM\Column]
  #[Assert\Sequentially([
    new Assert\Type(
      type: 'datetimeimmutable',
      message: 'Date invalide.'
    ),
    new Assert\LessThanOrEqual(
      value: 'now',
      message: 'La date ne peut être antérieure à la date actuelle.'
    )
  ])]
  private \DateTimeImmutable $createdAt;

  #[ORM\Column]
  #[Assert\Sequentially([
    new Assert\Type(
      type: 'datetimeimmutable',
      message: 'Date invalide.'
    ),
    new Assert\LessThanOrEqual(
      value: 'now',
      message: 'La date ne peut être antérieure à la date actuelle.'
    )
  ])]
  private \DateTimeImmutable $updatedAt;

  public function getCreatedAt(): \DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): \DateTimeImmutable
  {
    return $this->updatedAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
  {
    $this->updatedAt = $updatedAt;

    return $this;
  }
}
