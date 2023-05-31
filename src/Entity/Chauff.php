<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Ignore;
use App\Repository\ChauffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Lesmoyens;
use App\Entity\Clients;


#[ORM\Entity(repositoryClass: ChauffRepository::class)]
class Chauff 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numpermis = null;


    #[ORM\Column(type:"string")]
    private $etat;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Client $relation = null;
/** @Ignore() */
    #[ORM\OneToMany(mappedBy: 'chauff', targetEntity: Lesmoyens::class)]
    private Collection $moyens;


    public function setUser(User $userToSet) {
        $this->user=$userToSet;
    }
    public function __construct()
    {
        $this->moyens = new ArrayCollection();
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
        return $this->user->getUsername();
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
    public function getUser(): ?User
    {
        return $this->user;
    }


    public function getRelation(): ?Client
    {
        return $this->relation;
    }

    public function setRelation(?Client $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return Collection<int, Lesmoyens>
     */
    public function getMoyens(): Collection
    {
        return $this->moyens;
    }

    public function addMoyen(Lesmoyens $moyen): self
    {
        if (!$this->moyens->contains($moyen)) {
            $this->moyens->add($moyen);
            $moyen->setChauff($this);
        }

        return $this;
    }

    public function removeMoyen(Lesmoyens $moyen): self
    {
        if ($this->moyens->removeElement($moyen)) {
            // set the owning side to null (unless already changed)
            if ($moyen->getChauff() === $this) {
                $moyen->setChauff(null);
            }
        }

        return $this;
    }

}
