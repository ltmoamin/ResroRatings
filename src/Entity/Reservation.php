<?php

namespace App\Entity;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]

class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datereser = null;


    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $timereser = null;
   

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'iduser')]
    private ?User $user=null;    
   

    #[ORM\OneToOne(targetEntity: Restaurant::class)]
    #[ORM\JoinColumn(name: 'id_restau', referencedColumnName: 'id_restau')]
    private ?Restaurant $restaurant=null;

    public function getIdRes(): ?int
    {
        return $this->idRes;
    }

    public function getDatereser(): ?\DateTimeInterface
    {
        return $this->datereser;
    }

    public function setDatereser(\DateTimeInterface $datereser): static
    {
        $this->datereser = $datereser;

        return $this;
    }

    public function getTimereser(): ?\DateTimeInterface
    {
        return $this->timereser;
    }

    public function setTimereser(\DateTimeInterface $timereser): static
    {
        $this->timereser = $timereser;

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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }


}
