<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\DocumentController;



/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 *  @Vich\Uploadable
 * *  @ApiResource(
 *     iri="https://schema.org/Document",
 *     shortName="Place",
 *     collectionOperations={
 *          "post" = {
 *              "denormalization_context" = {
 *                  "groups"={
 *                      "document:collection:post"
 *                  }
 *              }
 *          },
 *          "get" = {
 *              "normalization_context" = {
 *                  "groups"={
 *                      "document:collection:get"
 *                  }
 *              }
 *          }
 *     },
 *     itemOperations={
 *          "get",
 *          "DocumentController::OPERATION_NAME" = {
 *              "groups"={"logo:post"},
 *              "method"="POST",
 *              "path"="/documents/{id}/file",
 *              "controller"=DocumentController::class,
 *              "deserialize"=false,
 *              "validation_groups"={"Default", "logo_create"},
 *              "openapi_context"={
 *                  "summary"="Uploads logo file to given Document resource",
 *                  "requestBody"={
 *                      "content"={
 *                          "multipart/form-data"={
 *                              "schema"={
 *                                  "type"="object",
 *                                  "properties"={
 *                                      "logoFile"={
 *                                          "type"="string",
 *                                          "format"="binary"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          }
 *     }
 * )
 * 
 */
// #[ApiResource(
    // iri: 'http://schema.org/MediaObject',
    // normalizationContext: ['groups' => ['media_object:read']],
    // itemOperations: ['get'],
    // collectionOperations: [
    //     'get',
    //     'post' => [
    //         'controller' => DocumentController::class,
    //         'deserialize' => false,
    //         'validation_groups' => ['Default', 'media_object_create'],
    //         'openapi_context' => [
    //             'requestBody' => [
    //                 'content' => [
    //                     'multipart/form-data' => [
    //                         'schema' => [
    //                             'type' => 'object',
    //                             'properties' => [
    //                                 'file' => [
    //                                     'type' => 'string',
    //                                     'format' => 'binary',
    //                                 ],
    //                             ],
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ],
    //     ],
    // ]
// )]
class Document
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("document:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("document:read")
     * @Groups("document:write")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups("document:read")
     * @Groups("document:write")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups("document:read")
     * @Groups("document:write")
     */
    private $urlComplet;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups("document:read")
     * @Groups("document:write")
     */
    private $Etat;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups("document:read")
    * @Groups("document:write")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     *  @Groups("document:read")
     * @Groups("document:write")
     */
    private $taille;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("document:write")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * 
     *  @Groups("document:read")
     * @Groups("document:write")
     */
    private $version;

    

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="document")
     * @Groups("document:read")
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=Tag::class, mappedBy="document")
     * @Groups("document:read")
     */
    private $tag;

    

    /**
     * @ORM\ManyToOne(targetEntity=Repertoire::class, inversedBy="document")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("document:read")
     */
    private $repertoire;

    

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="documents")
     * @Groups("document:read")
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(maxSize="1024k",mimeTypes={"application/pdf","image/jpg"})
     * @Groups("document:read")
     * @Vich\UploadableField(mapping="document_object", fileNameProperty="url")  
    
 
     
 */
    private $file;

    

    public function __construct()
    {
        $this->historique = new ArrayCollection();
        $this->commentaire = new ArrayCollection();
        $this->tag = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrlComplet(): ?string
    {
        return $this->urlComplet;
    }

    public function setUrlComplet(string $urlComplet): self
    {
        $this->urlComplet = $urlComplet;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(string $Etat): self
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    
    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire[] = $commentaire;
            $commentaire->setDocument($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getDocument() === $this) {
                $commentaire->setDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
            $tag->setDocument($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getDocument() === $this) {
                $tag->setDocument(null);
            }
        }

        return $this;
    }

   

    public function getRepertoire(): ?Repertoire
    {
        return $this->repertoire;
    }

    public function setRepertoire(?Repertoire $repertoire): self
    {
        $this->repertoire = $repertoire;

        return $this;
    }

   

    public function getAuteur(): ?Utilisateur
    {
        return $this->auteur;
    }

    public function setAuteur(?Utilisateur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    // public function __toString() {
    //     return $this->auteur->getNom();
    // }

    public function __toString()
    {
        return (string) $this->getNom();
    }
   
}
