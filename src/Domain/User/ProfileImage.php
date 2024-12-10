<?php

namespace App\Domain\User;

use App\Application\Traits\Entity\DatesTrait;
use App\Application\Traits\Entity\IdTrait;
use App\Infrastructure\Repository\ProfileImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProfileImageRepository::class)]
#[Vich\Uploadable]
class ProfileImage
{
    // Traits calls
    use IdTrait;
    use DatesTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'file_name', size: 'fileSize')]
    #[Assert\Image(
        allowLandscape: true,
        allowPortrait: true,
        maxSize: '5M',
        maxSizeMessage: 'Le fichier est trop volumineux. La taille maximale autorisée est de {{ limit }} Mo.',
        mimeTypes: ['image/jpeg', 'image/webp', 'image/jpg'],
        mimeTypesMessage: 'Le format de l\'image n\'est pas valide. Les formats valides sont {{ types }}.'
    )]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Sequentially([
        new Assert\Length(min: 5, max: 255, minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.', maxMessage: 'Ce champ est limité à {{ limit }} caractères.'),
        new Assert\Regex(pattern: '/^[a-zA-Z0-9.\s\(\)\-\'\"\p{L}]{5,255}+$/u', message: 'Ce champ contient des caractères non autorisés.')
    ])]
    private ?string $file_name = null;

    private ?int $fileSize = null;

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    public function __toString(): string
    {
        return $this->file_name ?? '';
    }
}
