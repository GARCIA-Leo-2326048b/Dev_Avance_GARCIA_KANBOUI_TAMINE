<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Repository\QcmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QcmRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']]
)]
class Qcm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Response>
     */
    #[ORM\OneToMany(targetEntity: Response::class, mappedBy: 'qcm', orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $responses;

    #[ORM\OneToOne(mappedBy: 'qcm', cascade: ['persist', 'remove'])]
    private ?Document $document = null;

    #[ORM\OneToOne(mappedBy: 'qcm', cascade: ['persist', 'remove'])]
    private ?Video $video = null;

    /**
     * Constructeur du QCM.
     *
     * Initialise la collection des réponses.
     */
    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant du QCM.
     *
     * @return int|null l'identifiant du QCM ou null s'il n'est pas encore enregistré
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le titre du QCM.
     *
     * @return string|null le titre du QCM ou null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre du QCM.
     *
     * @param string $title le titre du QCM
     * @return $this
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description du QCM.
     *
     * @return string|null la description du QCM ou null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description du QCM.
     *
     * @param string|null $description la description du QCM
     * @return $this
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne la liste des réponses associées au QCM.
     *
     * @return Collection<int, Response> la collection des réponses
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    /**
     * Ajoute une réponse au QCM.
     *
     * @param Response $response la réponse à ajouter
     * @return $this
     */
    public function addResponse(Response $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setQcm($this);
        }

        return $this;
    }

    /**
     * Supprime une réponse du QCM.
     *
     * @param Response $response la réponse à supprimer
     * @return $this
     */
    public function removeResponse(Response $response): static
    {
        $this->responses->removeElement($response);

        return $this;
    }

    /**
     * Retourne le document associé au QCM.
     *
     * @return Document|null le document associé ou null
     */
    public function getDocument(): ?Document
    {
        return $this->document;
    }

    /**
     * Définit le document associé au QCM.
     *
     * @param Document $document le document à associer
     * @return $this
     */
    public function setDocument(Document $document): static
    {
        if ($document->getQcm() !== $this) {
            $document->setQcm($this);
        }

        $this->document = $document;

        return $this;
    }

    /**
     * Retourne la vidéo associée au QCM.
     *
     * @return Video|null la vidéo associée ou null
     */
    public function getVideo(): ?Video
    {
        return $this->video;
    }

    /**
     * Définit la vidéo associée au QCM.
     *
     * @param Video $video la vidéo à associer
     * @return $this
     */
    public function setVideo(Video $video): static
    {
        if ($video->getQcm() !== $this) {
            $video->setQcm($this);
        }

        $this->video = $video;

        return $this;
    }
}
