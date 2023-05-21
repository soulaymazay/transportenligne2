<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientsRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'There is already an account with this nom')]
class Clients implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private $id ;

    #[ORM\Column(type:"string")]

    private  $username ;

    #[ORM\Column(type:"string")]
    private  $email ;

    #[ORM\Column(type:"simple_array")]
    private  $roles;

    #[ORM\Column(type:"string")]
    private  $etat ;

    #[ORM\Column(type:"string")]
    private  $password ;


    // #[ORM\Column(type: 'boolean')]
    // private $isVerified = false;

    public function getUserIdentifier() {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    public function __construct()
    {

    }
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
    public function getEtat()
    {
        return $this->etat;
    }
    public function setEtat(string $etat): void
    {
        $this->etat = $etat;
    }

    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    // public function setIsVerified(bool $isVerified): self
    // {
    //     $this->isVerified = $isVerified;

    //     return $this;
    // }

    

       
    
}
