<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 * * @ApiResource(
 * normalizationContext={"groups"={"commentaire:read"}},
 * denormalizationContext={"groups"={"commentaire:write"}}
 * )

 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("commentaire:read")

     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups("commentaire:read")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="commentaire")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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
}
