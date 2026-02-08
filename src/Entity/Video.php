<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['Video:read']],
    denormalizationContext: ['groups' => ['Video:write']]
)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Video:read', 'qcm:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Video:read', 'Video:write'])]
    private ?string $link = null;

    #[ORM\Column]
    #[Groups(['Video:read', 'Video:write'])]
    private ?int $duration = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Video:read', 'Video:write', 'qcm:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['Video:read', 'Video:write'])]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Video:read', 'Video:write', 'qcm:read'])]
    private ?User $teacher = null;

    #[ORM\OneToOne(inversedBy: 'video', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['Video:read', 'Video:write'])]
    private ?Qcm $qcm = null;

    /**
     * Retourne l'identifiant de la vidéo.
     *
     * @return int|null l'identifiant de la vidéo ou null si non enregistrée
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le lien de la vidéo.
     *
     * @return string|null le lien de la vidéo ou null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Définit le lien de la vidéo.
     *
     * @param string $link le lien de la vidéo
     * @return $this
     */
    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Retourne la durée de la vidéo.
     *
     * @return int|null la durée de la vidéo ou null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * Définit la durée de la vidéo.
     *
     * @param int $duration la durée de la vidéo (en secondes)
     * @return $this
     */
    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Retourne le titre de la vidéo.
     *
     * @return string|null le titre de la vidéo ou null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la vidéo.
     *
     * @param string $title le titre de la vidéo
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description de la vidéo.
     *
     * @return string|null la description de la vidéo ou null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la vidéo.
     *
     * @param string|null $description la description de la vidéo
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne l'enseignant associé à la vidéo.
     *
     * @return User|null l'enseignant associé
     */
    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    /**
     * Définit l'enseignant associé à la vidéo.
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
     * Retourne le QCM associé à la vidéo.
     *
     * @return Qcm|null le QCM associé
     */
    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    /**
     * Définit le QCM associé à la vidéo.
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
