<?php

namespace App\Application\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameNumberTrait
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
      pattern: '/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-]{2,255}$/u',
      message: 'Ce champ ne peut contenir que des lettres (majuscules et minuscules), des chiffres et des tirets.'
    )
  ])]
  private ?string $nameNumber = null;

  public function getNameNumber(): ?string
  {
    return $this->nameNumber;
  }

  public function setNameNumber(string $nameNumber): static
  {
    $this->nameNumber = $nameNumber;

    return $this;
  }
}
