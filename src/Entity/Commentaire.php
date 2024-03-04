<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("commmentaires")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Champ obligatoire")]
    #[Assert\Length(
        min:5,
        max: 300,
        minMessage: 'Le titre doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères',
    )]
    #[Groups("commmentaires")]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("commmentaires")]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[Groups("commmentaires")]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $user ;

    #[ORM\Column]
    #[Groups("commmentaires")]
    private ?int $nblike = null;

    #[ORM\Column]
    #[Groups("commmentaires")]
    private ?int $nbdislike = null;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'commentaire', targetEntity: Dislike::class)]
    private Collection $dislikes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNblike(): ?int
    {
        return $this->nblike;
    }

    public function setNblike(int $nblike): self
    {
        $this->nblike = $nblike;

        return $this;
    }

    public function getNbdislike(): ?int
    {
        return $this->nbdislike;
    }

    public function setNbdislike(int $nbdislike): self
    {
        $this->nbdislike = $nbdislike;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setCommentaire($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getCommentaire() === $this) {
                $like->setCommentaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dislike>
     */
    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(Dislike $dislike): self
    {
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes->add($dislike);
            $dislike->setCommentaire($this);
        }

        return $this;
    }

    public function removeDislike(Dislike $dislike): self
    {
        if ($this->dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getCommentaire() === $this) {
                $dislike->setCommentaire(null);
            }
        }

        return $this;
    }

}