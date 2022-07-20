<?php

namespace App\Entity;

use App\Entity\Legume;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaraicherRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: MaraicherRepository::class)]
class Maraicher implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $ville;

    #[ORM\Column(type: 'string', length: 255)]
    private $entreprise;

    #[ORM\Column(type: 'string', length: 255)]
    private $logo;

    #[ORM\OneToMany(mappedBy: 'maraicher', targetEntity: Legume::class, orphanRemoval: true)]
    private $legumes;

    #[ORM\Column(type: 'string', length: 3)]
    private $n_dpt;

    #[ORM\Column(type: 'string', length: 255)]
    private $n_rue;

    #[ORM\Column(type: 'string', length: 255)]
    private $rue;

    #[ORM\OneToMany(mappedBy: 'maraicher', targetEntity: Commande::class)]
    private $commandes;

    #[ORM\Column(type: 'string', length: 255)]
    private $cd_postal;

    public function __construct()
    {
        $this->legumes = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->entreprise;
    }

    public function getId(): ?int
    {
        return $this->id;
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

   /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_MAR';

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
    public function getPassword(): null | string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection<int, Legume>
     */
    public function getLegumes(): Collection
    {
        return $this->legumes;
    }

    public function addLegume(Legume $legume): self
    {
        if (!$this->legumes->contains($legume)) {
            $this->legumes[] = $legume;
            $legume->setMaraicher($this);
        }

        return $this;
    }

    public function removeLegume(Legume $legume): self
    {
        if ($this->legumes->removeElement($legume)) {
            // set the owning side to null (unless already changed)
            if ($legume->getMaraicher() === $this) {
                $legume->setMaraicher(null);
            }
        }

        return $this;
    }

    public function getNDpt(): ?string
    {
        return $this->n_dpt;
    }

    public function setNDpt(string $n_dpt): self
    {
        $this->n_dpt = $n_dpt;

        return $this;
    }

    public function getNRue(): ?string
    {
        return $this->n_rue;
    }

    public function setNRue(string $n_rue): self
    {
        $this->n_rue = $n_rue;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setMaraicher($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getMaraicher() === $this) {
                $commande->setMaraicher(null);
            }
        }

        return $this;
    }

    public function getCdPostal(): ?string
    {
        return $this->cd_postal;
    }

    public function setCdPostal(string $cd_postal): self
    {
        $this->cd_postal = $cd_postal;

        return $this;
    }
}
