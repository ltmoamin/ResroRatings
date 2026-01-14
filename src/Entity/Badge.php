<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le champ commantaire ne peut pas être vide')]
    #[Assert\Length(min: 3, minMessage: 'Le commantaire doit comporter au moins {{ limit }} caractères')]
    private ?string $commantaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datebadge = null;  
    

    #[ORM\Column(length: 255)]
    private ?string $typebadge = null;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'iduser', referencedColumnName: 'iduser')]
    private ?User $user=null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(name: 'id_restau', referencedColumnName: 'id_restau')]
    private ?Restaurant $restaurant = null;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommantaire(): ?string
    {
        return $this->commantaire;
    }

    public function setCommantaire(string $commantaire): static
    {
        $this->commantaire = $commantaire;

        return $this;
    }

    public function getDatebadge(): ?\DateTimeInterface
    {
        return $this->datebadge;
    }

    public function setDatebadge(\DateTimeInterface $datebadge): static
    {
        $this->datebadge = $datebadge;

        return $this;
    }

    public function getTypebadge(): ?string
    {
        return $this->typebadge;
    }

    public function setTypebadge(string $typebadge): static
    {
        $this->typebadge = $typebadge;

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

    #[ORM\Column]
    private ?int $likes = 0;
   

    #[ORM\Column]
    private ?int $dislikes = 0;

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }

    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): void
    {
        $this->dislikes = $dislikes;
    }

    public function incrementLikes(): void
    {
        $this->likes++;
    }

    public function incrementDislikes(): void
    {
        $this->dislikes++;
    }
    public function checkAndDeleteIfRequired(): bool
    {
        return $this->dislikes - $this->likes >= 2;
    }






}