<?php

namespace App\Entity;

use App\Traits\Entity\IdTrait;
use App\Repository\RideCommentRepository;
use App\Traits\Entity\DatesTrait;
use App\Traits\Entity\TextTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RideCommentRepository::class)]
class RideComment
{

    use DatesTrait;
    use IdTrait;

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
        maxMessage: 'Ce champ doit contenir au maximum {{ limit }} caractères.',
      ),
      new Assert\Regex(
        pattern: '/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-\s,?\':!()]{2,255}$/u',
        message: 'Le message contient des caractères non autorisés.'
      )
    ])]
    private ?string $text = null;
  
    #[ORM\ManyToOne(inversedBy: 'rideComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'rideComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ride $ride = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
      return $this->text;
    }
  
    public function setText(string $text): static
    {
      $this->text = $text;
  
      return $this;
    }


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getRide(): ?Ride
    {
        return $this->ride;
    }

    public function setRide(?Ride $ride): static
    {
        $this->ride = $ride;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->author . " : " . $this->text;
    }
}
