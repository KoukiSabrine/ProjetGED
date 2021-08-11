<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;




/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(fields={"username"}, message="It looks like your already have an account!")
 * @ApiResource(
 * normalizationContext={"groups"={"utilisateur:read"}},
 * denormalizationContext={"groups"={"utilisateur:write"}}
 * )

 */
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups("utilisateur:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Groups("utilisateur:read")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * 
     * @Groups("utilisateur:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Groups("utilisateur:read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("utilisateur:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Groups("utilisateur:read")
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="gerant")
     */
    private $equipes;

    /**
     * @ORM\ManyToMany(targetEntity=Equipe::class, mappedBy="membre")
     */
    private $equipe;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="auteur")
     */
    private $documents;

    public function __construct()
    {
        $this->equipes = new ArrayCollection();
        $this->equipe = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->setGerant($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getGerant() === $this) {
                $equipe->setGerant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setAuteur($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getAuteur() === $this) {
                $document->setAuteur(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->prenom;
    }

   
}
