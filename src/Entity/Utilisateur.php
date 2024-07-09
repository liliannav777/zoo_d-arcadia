<?php

// src/Entity/Utilisateur.php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(name: 'role_id', referencedColumnName: 'role_id', nullable: false)]
    private ?Role $role = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: RapportVeterinaire::class)]
    private Collection $rapportsVeterinaires;

    public function __construct(string $username = '', string $password = '', string $nom = '', string $prenom = '')
    {
        $this->username = $username;
        $this->password = $password;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->rapportsVeterinaires = new ArrayCollection();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role ? $this->role->getLabel() : 'ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        // Pas nécessaire pour l'encodeur bcrypt
        return null;
    }

    public function eraseCredentials()
    {
        // Si tu stockes des données temporaires, vide ici
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @return Collection<int, RapportVeterinaire>
     */
    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }

    public function addRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        if (!$this->rapportsVeterinaires->contains($rapportVeterinaire)) {
            $this->rapportsVeterinaires->add($rapportVeterinaire);
            $rapportVeterinaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        if ($this->rapportsVeterinaires->removeElement($rapportVeterinaire)) {
            if ($rapportVeterinaire->getUtilisateur() === $this) {
                $rapportVeterinaire->setUtilisateur(null);
            }
        }

        return $this;
    }
}
