<?php

namespace App\Entity;

use App\Repository\ChauffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Lesmoyens;
use App\Entity\Clients;


#[ORM\Entity(repositoryClass: ChauffRepository::class)]
class Chauff implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numpermis = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;
    #[ORM\Column(type:"simple_array")]
    private $roles;

    #[ORM\Column(type:"string")]
    private $etat;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?clients $relation = null;

    #[ORM\ManyToMany(targetEntity: Lesmoyens::class, mappedBy: 'relation')]
    private Collection $lesmoyens;

    public function __construct()
    {
        $this->lesmoyens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumpermis(): ?string
    {
        return $this->numpermis;
    }

    public function setNumpermis(string $numpermis): self
    {
        $this->numpermis = $numpermis;

        return $this;
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    // Implémentation des méthodes de l'interface UserInterface

    public function getSalt()
    {
        // Non nécessaire avec l'algorithme bcrypt utilisé pour le hachage du mot de passe
    }

    public function eraseCredentials()
    {
        // Cette méthode n'a pas de logique à implémenter dans ce cas-ci
    }
    public function getUserIdentifier()
    {

    }

    public function getRelation(): ?clients
    {
        return $this->relation;
    }

    public function setRelation(?Clients $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return Collection<int, Lesmoyens>
     */
    public function getLesmoyens(): Collection
    {
        return $this->lesmoyens;
    }

    public function addLesmoyen(Lesmoyens $lesmoyen): self
    {
        if (!$this->lesmoyens->contains($lesmoyen)) {
            $this->lesmoyens->add($lesmoyen);
            $lesmoyen->addRelation($this);
        }

        return $this;
    }

    public function removeLesmoyen(Lesmoyens $lesmoyen): self
    {
        if ($this->lesmoyens->removeElement($lesmoyen)) {
            $lesmoyen->removeRelation($this);
        }

        return $this;
    }
}
