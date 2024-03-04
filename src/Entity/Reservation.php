<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Etat = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Evenement $Evenement = null;

    
    #[ORM\ManyToOne(inversedBy: 'reservations',cascade: ["persist"])]
    private ?User $User = null;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'Reservation')]
    private Collection $participations;

    #[ORM\Column]
    private ?int $NbParticipant = null;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->Evenement;
    }

    public function setEvenement(?Evenement $Evenement): static
    {
        $this->Evenement = $Evenement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setReservation($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getReservation() === $this) {
                $participation->setReservation(null);
            }
        }

        return $this;
    }

    public function getNbParticipant(): ?int
    {
        return $this->NbParticipant;
    }

    public function setNbParticipant(int $NbParticipant): static
    {
        $this->NbParticipant = $NbParticipant;

        return $this;
    }
}
