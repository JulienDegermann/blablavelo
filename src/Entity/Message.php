<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Traits\Entity\DatesTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use App\Traits\Entity\TextTrait;
use App\Traits\Entity\TitleTrait;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    // Traits calls
    use IdTrait;
    use TitleTrait;
    use DatesTrait;
    use TextTrait;

    #[ORM\ManyToOne(inversedBy: 'messages', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id')]
    #[Assert\Valid]
    private ?User $author = null;

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
