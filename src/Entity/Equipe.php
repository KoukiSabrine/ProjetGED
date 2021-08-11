<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 * * @ApiResource(
 * normalizationContext={"groups"={"equipe:read"}},
 * denormalizationContext={"groups"={"equipe:write"}}
 * )

 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     *  @Groups("equipe:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("equipe:read")
     */
    private $nomEq;

    /**
     * @ORM\ManyToOne(targetEntity=Projet::class, inversedBy="equipe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="equipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gerant;

    /**
     * @ORM\ManyToMany(targetEntity=Utilisateur::class, inversedBy="equipe")
     */
    private $membre;

    public function __construct()
    {
        $this->membre = new ArrayCollection();
    }

    
    public function setId(int $nom): self
    {
        $this->id = $nom;

        return $this;
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nomEq;
    }

    public function setNom(string $nom): self
    {
        $this->nomEq = $nom;

        return $this;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getGerant(): ?Utilisateur
    {
        return $this->gerant;
    }

    public function setGerant(?Utilisateur $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    /**
     * @return Collection|Utilisateur[]
     */
    public function getMembre(): Collection
    {
        return $this->membre;
    }

    public function addMembre(Utilisateur $membre): self
    {
        if (!$this->membre->contains($membre)) {
            $this->membre[] = $membre;
        }

        return $this;
    }

    public function removeMembre(Utilisateur $membre): self
    {
        $this->membre->removeElement($membre);

        return $this;
    }
    public function __toString() {
        return $this->nomEq;
    }

    

    

    
    

  
}
