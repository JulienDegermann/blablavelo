<?php

namespace App\Application\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameTrait
{

  #[ORM\Column(length: 255)]
  #[Assert\Sequentially([
    new Assert\NotBlank(
      message: 'Ce champ est obligatoire.'
    ),
    new Assert\Type(
      type: 'string',
      message: 'Ce champ doit être une chaine de caractères.'
    ),
    new Assert\Length(
      min: 2,
      max: 255,
      minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
      maxMessage: 'Ce champ ne peut dépasser {{ limit }} caractères.'
    ),
    new Assert\Regex(
      pattern: '/^(?![×Þß÷þø])[a-zA-ZÀ-ÿ\-\s]{2,255}$/u',
      message: 'Ce champ ne peut contenir que des lettres (majuscules et minuscules) et des tirets.'
    )
  ])]
  private ?string $name = null;

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }
}
