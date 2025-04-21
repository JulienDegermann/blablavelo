<?php

namespace App\Application\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;

trait IdTrait
{

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  public function getId(): ?int
  {
    return $this->id;
  }
}
