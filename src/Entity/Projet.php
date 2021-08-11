<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ORM\Entity(repositoryClass=ProjetRepository::class)
 * * @ApiResource(
 * normalizationContext={"groups"={"projet:read"}},
 * denormalizationContext={"groups"={"projet:write"}}
 * )

 */
class Projet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("projet:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("projet:read")
     */
    private $titre;

    

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("projet:read")
     */
    private $etat;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Groups("projet:read")
     */
    private $niveau;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups("projet:read")
     */
    private $dateLancement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Groups("projet:read")
     */
    private $dureePrevue;

    /**
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="projet")
     * @Groups("projet:read")
     * @ApiSubresource
     */
    private $equipe;

    public function __construct()
    {
        $this->equipe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
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

    public function getNiveau(): ?float
    {
        return $this->niveau;
    }

    public function setNiveau(?float $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(?\DateTimeInterface $dateLancement): self
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getDureePrevue(): ?int
    {
        return $this->dureePrevue;
    }

    public function setDureePrevue(?int $dureePrevue): self
    {
        $this->dureePrevue = $dureePrevue;

        return $this;
    }
}
