<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['Document:read']],
    denormalizationContext: ['groups' => ['Document:write']]
)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Document:read', 'qcm:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Document:read', 'Document:write'])]
    private ?string $link = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Document:read', 'Document:write', 'qcm:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['Document:read', 'Document:write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['Document:read', 'Document:write'])]
    private ?int $pages = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Document:read', 'Document:write', 'qcm:read'])]
    private ?User $teacher = null;

    #[ORM\OneToOne(inversedBy: 'document', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['Document:read', 'Document:write'])]
    private ?Qcm $qcm = null;

    /**
     * Retourne l'identifiant du document.
     *
     * @return int|null l'identifiant du document ou null s'il n'est pas encore enregistré
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le lien du document.
     *
     * @return string|null le lien du document ou null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Définit le lien du document.
     *
     * @param string $link le lien du document
     * @return $this
     */
    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Retourne le titre du document.
     *
     * @return string|null le titre du document ou null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre du document.
     *
     * @param string $title le titre du document
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description du document.
     *
     * @return string|null la description du document ou null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du document.
     *
     * @param string|null $description la description du document
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne le nombre de pages du document.
     *
     * @return int|null le nombre de pages ou null
     */
    public function getPages(): ?int
    {
        return $this->pages;
    }

    /**
     * Définit le nombre de pages du document.
     *
     * @param int $pages le nombre de pages
     * @return $this
     */
    public function setPages(int $pages): static
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Retourne l'enseignant associé au document.
     *
     * @return User|null l'enseignant associé
     */
    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    /**
     * Définit l'enseignant associé au document.
     *
     * @param User|null $teacher l'enseignant
     * @return $this
     */
    public function setTeacher(?User $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Retourne le QCM associé au document.
     *
     * @return Qcm|null le QCM associé
     */
    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    /**
     * Définit le QCM associé au document.
     *
     * @param Qcm $qcm le QCM à associer
     * @return $this
     */
    public function setQcm(Qcm $qcm): static
    {
        $this->qcm = $qcm;

        return $this;
    }
}
