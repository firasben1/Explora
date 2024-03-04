<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    private ?string $Categories = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    private ?string $Marque = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    private ?string $Couleur = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    private ?string $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

   // #[ORM\Column(length: 255, nullable: true)]
   // private $rating;

    // Getters et setters pour le champ de notation
   // public function getRating(): ?float
    //{
      //  return $this->rating;
    //}

    //public function setRating(?float $rating): self
    //{
      //  $this->rating = $rating;

        //return $this;
    //}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategories(): ?string
    {
        return $this->Categories;
    }

    public function setCategories(?string $Categories): static
    {
        $this->Categories = $Categories;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->Marque;
    }

    public function setMarque(?string $Marque): static
    {
        $this->Marque = $Marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->Couleur;
    }

    public function setCouleur(?string $Couleur): static
    {
        $this->Couleur = $Couleur;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
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

    private $imageName;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Location::class)]
    private Collection $Location;

    public function __construct()
    {
        $this->Location = new ArrayCollection();
    }

    // getter and setter for imageName
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocation(): Collection
    {
        return $this->Location;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->Location->contains($location)) {
            $this->Location->add($location);
            $location->setVoiture($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->Location->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getVoiture() === $this) {
                $location->setVoiture(null);
            }
        }

        return $this;
    }
    
}