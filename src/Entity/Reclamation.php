<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;




#[Vich\Uploadable]

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]

    private ?string $Titre = null;
    

    #[ORM\Column(length: 255, nullable: true)]
    
    private ?string $statut = 'En cours';

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    private ?string $description = null;


    #[ORM\Column(length: 255, nullable: true)]
    
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'image')]

    private ?File $imageFile = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    //#[ORM\OneToOne(mappedBy: 'reclamation', cascade: ['persist', 'remove'])]
   
    private ?Reponse $reponse = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?User $user = null;
   
    
    public function getId(): ?int
    {
        return $this->id;
    }

   
    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }
    
    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        // Check if the date is not already set
        if ($this->date === null) {
            // Set the date property to the current system date
            $this->date = new \DateTimeImmutable();
        }
        
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            // $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): static
    {
        // unset the owning side of the relation if necessary
        if ($reponse === null && $this->reponse !== null) {
            $this->reponse->setReclamation(null);
        }

        // set the owning side of the relation if necessary
        if ($reponse !== null && $reponse->getReclamation() !== $this) {
            $reponse->setReclamation($this);
        }

        $this->reponse = $reponse;

        return $this;
    }
    public function __toString(): string
{
    return $this->getTitre(); // Return the title of the reclamation as the string representation
}

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }






}