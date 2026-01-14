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
    private ?int $idrep = null;

    #[ORM\Column(length: 255)]
    private ?string $contenue = null;
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $daterep = null;


    #[ORM\OneToOne(targetEntity: Reclamation::class)]
    #[ORM\JoinColumn(name: 'idrec', referencedColumnName: 'idrec')]
    private ?Reclamation $reclamation=null;

    public function getIdrep(): ?int
    {
        return $this->idrep;
    }

    public function getContenue(): ?string
    {
        return $this->contenue;
    }

    public function setContenue(string $contenue): static
    {
        $this->contenue = $contenue;

        return $this;
    }

    public function getDaterep(): ?\DateTimeInterface
    {
        return $this->daterep;
    }

    public function setDaterep(\DateTimeInterface $daterep): static
    {
        $this->daterep = $daterep;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): static
    {
        $this->reclamation = $reclamation;

        return $this;
    }


}
