<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=HistoriqueRepository::class)
 * * @ApiResource(
 * normalizationContext={"groups"={"historique:read"}},
 * denormalizationContext={"groups"={"historique:write"}}
 * )

 */
class Historique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("historique:read")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModif;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="historique")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $aut;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("historique:read")
     */
    private $versionDoc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("historique:read")
     */
    private $remarque;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("historique:read")
     */
    private $etatDoc;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(mimeTypes={"application/pdf"})     */
     private $fileHis;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getAut(): ?Utilisateur
    {
        return $this->aut;
    }

    public function setAut(?Utilisateur $aut): self
    {
        $this->aut = $aut;

        return $this;
    }

    public function getVersionDoc(): ?string
    {
        return $this->versionDoc;
    }

    public function setVersionDoc(string $version): self
    {
        $this->versionDoc = $version;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }

    public function getEtatDoc(): ?string
    {
        return $this->etatDoc;
    }

    public function setEtatDoc(string $etatDoc): self
    {
        $this->etatDoc = $etatDoc;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->fileHis;
    }

    public function setFile(string $file): self
    {
        $this->fileHis = $file;

        return $this;
    }
}
