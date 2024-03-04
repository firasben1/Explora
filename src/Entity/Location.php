<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $Mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:8,
        max: 8,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
    
    )]
    private ?int $Mobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:8,
        max: 8,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
        )]

    private ?string $Adresse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?\DateTimeInterface $Date_prise = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?\DateTimeInterface $Date_depot = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:8,
        max: 8,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
    
    )]
    private ?int $Num_Cin = null;

    #[ORM\Column(nullable: true)]
    private ?int $Prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    private ?string $permis_conduite = null;

   
    #[ORM\ManyToOne(inversedBy: 'Location')]
    private ?Voiture $voiture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chauffeur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(?string $Mail): static
    {
        $this->Mail = $Mail;

        return $this;
    }

    public function getMobile(): ?int
    {
        return $this->Mobile;
    }

    public function setMobile(?int $Mobile): static
    {
        $this->Mobile = $Mobile;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getDatePrise(): ?\DateTimeInterface
    {
        return $this->Date_prise;
    }

    public function setDatePrise(?\DateTimeInterface $Date_prise): static
    {
        $this->Date_prise = $Date_prise;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->Date_depot;
    }

    public function setDateDepot(?\DateTimeInterface $Date_depot): static
    {
        $this->Date_depot = $Date_depot;

        return $this;
    }

    public function getNumCin(): ?int
    {
        return $this->Num_Cin;
    }

    public function setNumCin(?int $Num_Cin): static
    {
        $this->Num_Cin = $Num_Cin;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(?int $Prix): static
    {
        $this->Prix = $Prix;

        return $this;
    }

    public function getPermisConduite(): ?string
    {
        return $this->permis_conduite;
    }

    public function setPermisConduite(?string $permis_conduite): static
    {
        $this->permis_conduite = $permis_conduite;

        return $this;
    }


 

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): static
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function getChauffeur(): ?string
    {
        return $this->chauffeur;
    }

    public function setChauffeur(?string $chauffeur): static
    {
        $this->chauffeur = $chauffeur;

        return $this;
    }
    
    private ?string $authCode = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ReponseLocation $reponseLocation = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    private ?User $user = null;

   

    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    public function setAuthCode(?string $authCode): self
    {
        $this->authCode = $authCode;

        return $this;
    }

    public function getReponseLocation(): ?ReponseLocation
    {
        return $this->reponseLocation;
    }

    public function setReponseLocation(?ReponseLocation $reponseLocation): static
    {
        $this->reponseLocation = $reponseLocation;

        return $this;
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