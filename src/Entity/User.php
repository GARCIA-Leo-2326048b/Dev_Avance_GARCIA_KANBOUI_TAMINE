<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> rôles de l'utilisateur
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Mot de passe hashé de l'utilisateur
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    /**
     * Retourne l'identifiant de l'utilisateur.
     *
     * @return int|null l'identifiant ou null si non enregistré
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'email de l'utilisateur.
     *
     * @return string|null l'email ou null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'email de l'utilisateur.
     *
     * @param string $email l'email de l'utilisateur
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Retourne l'identifiant visuel de l'utilisateur.
     *
     * Utilisé par Symfony pour l'authentification.
     *
     * @return string l'identifiant de l'utilisateur (email)
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Retourne les rôles de l'utilisateur.
     *
     * Le rôle ROLE_USER est toujours ajouté par défaut.
     *
     * @return array la liste des rôles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles la liste des rôles
     * @return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe hashé de l'utilisateur.
     *
     * @return string|null le mot de passe hashé
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe hashé de l'utilisateur.
     *
     * @param string $password le mot de passe hashé
     * @return $this
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Supprime les données sensibles temporaires de l'utilisateur.
     *
     * Méthode requise par Symfony (actuellement inutilisée).
     */
    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // Rien à faire pour le moment
    }

    /**
     * Retourne le prénom de l'utilisateur.
     *
     * @return string|null le prénom
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le prénom de l'utilisateur.
     *
     * @param string $name le prénom
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne le nom de famille de l'utilisateur.
     *
     * @return string|null le nom de famille
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Définit le nom de famille de l'utilisateur.
     *
     * @param string $surname le nom de famille
     * @return $this
     */
    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }
}
