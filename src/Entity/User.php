<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Stmt\Static_;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $iduser = null;

    #[ORM\Column(length: 30)]
    private ?string $username = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "L'adresse email n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $role = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "The password is mandatory")]
    private ?string $password = null;
    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "The first name is mandatory")]
    private ?string $firstname = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: "The last name is mandatory")]
    private ?string $lastname = null;

    #[ORM\Column(nullable: true, length: 20)]
    private ?string $tel = null;

    #[ORM\Column(nullable: true, length: 300)]
    private ?string $address = null;


    #[ORM\Column(length: 180, nullable: true)]
    private ?string $reset_token = null;

    #[ORM\Column(type: "boolean")]
    #[Groups("users")]
    private $isBlocked = false;

    #[ORM\Column(type: "boolean")]
    #[Groups("users")]
    private $isApproved = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['default' => 'Actif'])]
    #[Groups("users")]
    private ?string $etat = "Actif";

    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'Actif'])]
    #[Groups("users")]
    private ?string $status = "Actif";

    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: "user")]
    private ?Collection $reclamations = null;


    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER


        return array_unique($roles);
    }
    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }


    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }
    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(?bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(?bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): void
    {
        $this->etat = $etat;
    }
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function getUserDataForQrCode(): string
    {
        $data = "Iduser: {$this->iduser}, Username: {$this->username}, Email: {$this->email}, Firstname: {$this->firstname}, Lastname: {$this->lastname}, Tel: {$this->tel}, Address: {$this->address}, Reset Token: {$this->reset_token}, Is Blocked: {$this->isBlocked}, Is Approved: {$this->isApproved}, Etat: {$this->etat}, Status: {$this->status}";

        return $data;
    }
    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }
    /**
     * @return Collection|Reclamation[]
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations ?: new ArrayCollection();
    }

}
