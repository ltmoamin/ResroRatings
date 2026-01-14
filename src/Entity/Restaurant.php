<?php

namespace App\Entity;
use App\Repository\RestaurantRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
   
    #[ORM\Column]
    
    private ?int $id_restau = null;

     
     
    
   
   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    
   
    private ?string $nom = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $location = null;

  
    public function getIdRestau(): ?int
    {
        return $this->id_restau;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }



   


}