<?php

namespace App\Traits\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

trait ImageTrait
{

  use DatesTrait;
  
  #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'fileName', size: 'fileSize')]
  #[Assert\Image(
    allowLandscape: true,
    allowPortrait: true,
    maxSize: '5M',
    maxSizeMessage: 'Le fichier est trop volumineux. La taille maximale autorisée est de {{ limit }} Mo.',
    mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'],
    mimeTypesMessage: 'Le format de l\'image n\'est pas valide. Les formats valides sont {{ types }}.'
  )]
  private ?File $file = null;


  #[ORM\Column(length: 255, nullable: true)]
  // #[Assert\Sequentially([
  //   new Assert\Length(min: 5, max: 255, minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.', maxMessage: 'Ce champ est limité à {{ limit }} caractères.'),
  //   new Assert\Regex(pattern: '/^[a-zA-Z0-9.\s\(\)\-\'\"\p{L}]{5,255}+$/u', message: 'Ce champ contient des caractères non autorisés.')
  // ])]
  private ?string $fileName = null;

  private ?int $fileSize = null;

  public function getFile(): ?File
  {
    return $this->file;
  }

  public function setFile(?File $file = null): void
  {
    $this->file = $file;

    if (null !== $file) {
      // It is required that at least one field changes if you are using doctrine
      // otherwise the event listeners won't be called and the file is lost
      $this->updatedAt = new \DateTimeImmutable();
    }
  }

  public function getfileName(): ?string
  {
    return $this->fileName;
  }

  public function setfileName(?string $fileName): static
  {
    $this->fileName = $fileName;

    return $this;
  }

  public function getfileSize(): ?int
  {
    return $this->fileSize;
  }

  public function setfileSize(?int $fileSize): static
  {
    $this->fileSize = $fileSize;

    return $this;
  }
}
