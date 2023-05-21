<?php

namespace App\Entity;

use App\Repository\LesmoyensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Chauff;

#[ORM\Entity(repositoryClass: LesmoyensRepository::class)]
class Lesmoyens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    private ?string $annee = null;
    #[ORM\Column(type:"simple_array")]
    private  $roles;

    #[ORM\Column(type:"string")]
    private  $etat ;

    #[ORM\ManyToMany(targetEntity: chauff::class, inversedBy: 'lesmoyens')]
    private Collection $relation;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

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

    /**
     * @return Collection<int, chauff>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(chauff $relation): self
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
        }

        return $this;
    }

    public function removeRelation(chauff $relation): self
    {
        $this->relation->removeElement($relation);

        return $this;
    }
}
