<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateReponse = null;

    #[ORM\OneToOne(inversedBy: 'reponse', cascade: ['persist', 'remove'])]
    private ?Reclamation $reclamation = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    
    
    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): static
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        // Check if the date is not already set
        if ($this->dateReponse === null) {
            // Set the date property to the current system date
            $this->dateReponse = new \DateTimeImmutable();
        }
        
        return $this->dateReponse;
    }

    public function setDateReponse(?\DateTimeInterface $dateReponse): static
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }
    
    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;
    
        // Ensure bidirectional consistency
        if ($reclamation !== null) {
            $reclamation->setReponse($this);
        }
    
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
}