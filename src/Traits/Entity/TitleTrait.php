<?php

namespace App\Traits\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

trait TitleTrait
{

  #[ORM\Column(length: 255)]
  #[Assert\Sequentially([
    new Assert\NotBlank(
      message: 'Ce champ est obligatoire.'
    ),
    new Assert\Type(
      type: 'string',
      message: 'Ce champ doit être une chaîne de caractères.'
    ),
    new Assert\Length(
      min: 2,
      max: 255,
      minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
      maxMessage: 'Ce champ ne peut dépasser {{ limit }} caractères.'
    ),
    new Assert\Regex(
      pattern: '/^[a-zA-Z\-/s\p{L}]{2, 255}$/u',
      message: 'Ce champ ne peut contenir que des lettres (majuscules et minuscules) et des tirets.'
    )
  ])]
  private ?string $title = null;

  public function getTitle(): ?string
  {
    return $this->Title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }
}
