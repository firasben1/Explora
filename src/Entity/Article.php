<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Vich\Uploadable]
 
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:5,
        max: 100,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
    
    )]
    
    private ?string $titre = null;

    #[ORM\Column(length: 65535)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:10,
        max: 11150,
        minMessage: 'La description doit comporter au moins {{ limit }} caractères',
        maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères',
    )]
    
    private ?string $description = null;
   
   
    #[Assert\Image(
        minWidth: 200,
        maxWidth: 5000,
        minHeight: 200,
        maxHeight: 5000,
    )]
    #[ORM\Column(nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;
    


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $nbcommentaire = null;



    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;
    
    
    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNbcommentaire(): ?int
    {
        return $this->nbcommentaire;
    }

    public function setNbcommentaire(int $nbcommentaire): self
    {
        $this->nbcommentaire = $nbcommentaire;

        return $this;
    }

   
    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    
    public function getImage(): ?string
    {
        return $this->image;
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