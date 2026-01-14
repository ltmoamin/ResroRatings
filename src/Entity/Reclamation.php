<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use App\Validator\Constraints as CustomAssert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idrec = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Date]
    #[Groups("reclamations")]
   // #[Assert\NotBlank(message: 'Ce champ est obligatoire')]

    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups("reclamations")]
    //#[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    private ?string $description = null;

    
    #[ORM\Column(length: 255)]
    #[Groups("reclamations")]
   // #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
  
   private ?string $typerec = null;

    
    #[ORM\Column(length: 255)]
    #[Groups("reclamations")]
   // #[Assert\NotBlank(message: 'Ce champ est obligatoire')]
    private ?string $etatrec = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'iduser')]
    private ?User $user=null;
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: "reclamation")]
    private ?Collection $reponses = null;

    public function getIdrec(): ?int
    {
        return $this->idrec;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTyperec(): ?string
    {
        return $this->typerec;
    }

    public function setTyperec(string $typerec): static
    {
        $this->typerec = $typerec;

        return $this;
    }

    public function getEtatrec(): ?string
    {
        return $this->etatrec;
    }

    public function setEtatrec(string $etatrec): static
    {
        $this->etatrec = $etatrec;

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
    public function getReponses(): ?Collection
    {
        return $this->reponses;
    }


}
