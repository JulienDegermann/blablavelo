<?php

namespace App\Traits\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

trait DatesTrait
{

  #[ORM\Column]
  #[Assert\Sequentially([
    new Assert\DateTime(
      message: 'Date invalide.'
    ),
    new Assert\LessThanOrEqual(
      value: 'now',
      message: 'La date ne peut être postérieure à la date actuelle.'
    )
  ])]
  private \DateTimeImmutable $createdAt;

  #[ORM\Column]
  #[Assert\Sequentially([
    new Assert\DateTime(
      message: 'Date invalide.'
    ),
    new Assert\LessThanOrEqual(
      value: 'now',
      message: 'La date ne peut être postérieure à la date actuelle.'
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
