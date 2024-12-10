<?php

namespace App\Application\Traits\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TextTrait
{

  #[ORM\Column(type: Types::TEXT)]
  #[Assert\Sequentially([
    // new Assert\NotBlank(
    //   message: 'Ce champ est obligatoire.'
    // ),
    new Assert\Type(
      type: 'string',
      message: 'Ce champ doit être une chaine de caractères.'
    ),
    new Assert\Length(
      min: 2,
      minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.',
    ),
    new Assert\Regex(
      pattern: '/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-\s,?\'()]{2,}$/u',
      message: 'Les caractères autorisés : lettres (minuscules et majuscules, accentuées ou non), chiffres, espaces, parenthèses, tirets et caractères de ponctuation'
    )
  ])]
  private ?string $text = null;

  public function getText(): ?string
  {
    return $this->text;
  }

  public function setText(string $text): static
  {
    $this->text = $text;

    return $this;
  }
}
