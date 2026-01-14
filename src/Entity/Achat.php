<?php
namespace App\Entity;
use App\Repository\AchatRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]

class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idachat = null;
   
    #[ORM\Column]
    private ?float $montanttotal = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'iduser')]
    private ?User $user=null;    
    
    #[ORM\OneToOne(targetEntity: Plat::class)]
    #[ORM\JoinColumn(name: 'idplat', referencedColumnName: 'idplat')]
    private ?Plat $plat=null;

    private $idplat;

    public function getIdachat(): ?int
    {
        return $this->idachat;
    }

    public function getMontanttotal(): ?float
    {
        return $this->montanttotal;
    }

    public function setMontanttotal(float $montanttotal): static
    {
        $this->montanttotal = $montanttotal;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdplat(): ?Plat
    {
        return $this->idplat;
    }

    public function setIdplat(?Plat $idplat): static
    {
        $this->idplat = $idplat;

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

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): static
    {
        $this->plat = $plat;

        return $this;
    }

    public function calculateTotalAmount(): ?float
    {
        $plat = $this->getPlat();
    
        if ($plat) {
            $quantite = $this->getQuantite();
    
            if ($this->getType() === 'livraison') {
                return ($plat->getPrix() * $quantite) ;
            } else {
                return $plat->getPrix() * $quantite;
            }
        }
    
        return null;
    }
}
