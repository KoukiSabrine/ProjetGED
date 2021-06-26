<?php

namespace App\Entity;

use App\Repository\RepertoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RepertoireRepository::class)
 */
class Repertoire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="repertoire", orphanRemoval=true, cascade={"persist"})
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity=Equipe::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipe;

    

   // cascade={"persist"}

    /**
     * @ORM\ManyToOne(targetEntity=Repertoire::class, inversedBy="sousRepertoire")
     */
    private $repertoire;

    /**
     * @ORM\OneToMany(targetEntity=Repertoire::class, mappedBy="repertoire")
     */
    private $sousRepertoire;

    public function __construct()
    {
        $this->document = new ArrayCollection();
        $this->sousRepertoire = new ArrayCollection();
    }

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

    /**
     * @return Collection|Document[]
     */
    public function getDocument(): Collection
    {
        return $this->document;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->document->contains($document)) {
            $this->document[] = $document;
            $document->setRepertoire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->document->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getRepertoire() === $this) {
                $document->setRepertoire(null);
            }
        }

        return $this;
    }

    public function getEquipe(): ?Equipe
    {
        return $this->equipe;
    }

    public function setEquipe(?Equipe $equipe): self
    {
        $this->equipe = $equipe;

        return $this;
    }



   

    public function getRepertoire(): ?self
    {
        return $this->repertoire;
    }

    public function setRepertoire(?self $repertoire): self
    {
        $this->repertoire = $repertoire;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSousRepertoire(): Collection
    {
        return $this->sousRepertoire;
    }

    public function addSousRepertoire(self $sousRepertoire): self
    {
        if (!$this->sousRepertoire->contains($sousRepertoire)) {
            $this->sousRepertoire[] = $sousRepertoire;
            $sousRepertoire->setRepertoire($this);
        }

        return $this;
    }

    public function removeSousRepertoire(self $sousRepertoire): self
    {
        if ($this->sousRepertoire->removeElement($sousRepertoire)) {
            // set the owning side to null (unless already changed)
            if ($sousRepertoire->getRepertoire() === $this) {
                $sousRepertoire->setRepertoire(null);
            }
        }

        return $this;
    }
}
