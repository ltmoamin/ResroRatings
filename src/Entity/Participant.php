<?php

namespace App\Entity;
use App\Repository\ParticipantRepository;
use App\Entity\Evennement;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]

class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private  ?int  $idparticipant = null;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datepar = null;

    #[ORM\Column]
    private ?int $numero = null;
  

       
    #[ORM\OneToOne(targetEntity: Evennement::class)]
    #[ORM\JoinColumn(name: 'idevent', referencedColumnName: 'idevent')]
    private ?Evennement $event = null;

   

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'iduser')]
    private ?User $user=null; 

    public function getIdparticipant(): ?int
    {
        return $this->idparticipant;
    }

    public function getDatepar(): ?\DateTimeInterface
    {
        return $this->datepar;
    }

    public function setDatepar(\DateTimeInterface $datepar): static
    {
        $this->datepar = $datepar;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getEvent(): ?Evennement
    {
        return $this->event;
    }

    public function setEvent(?Evennement $event): static
    {
        $this->event = $event;

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