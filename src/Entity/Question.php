<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Qcm;
use App\Entity\Response;

/**
 * L'entité Question représente une question appartenant à un QCM.
 * Elle contient l’énoncé de la question ainsi qu’une collection
 * de réponses possibles. Chaque question est obligatoirement
 * rattachée à un QCM.
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['Question:read']],
    denormalizationContext: ['groups' => ['Question:write']]
)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Question:read', 'Response:read', 'qcm:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Question:read', 'Question:write', 'Response:read', 'qcm:read', 'qcm:write'])]
    private ?string $question = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Question:read', 'Question:write'])]
    private ?Qcm $qcm = null;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Response::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['qcm:read', 'Question:read', 'qcm:write'])]
    private Collection $responses;

    /**
     * Initialise la collection de réponses.
     */
    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique de la question.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le texte de la question.
     *
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * Définit le texte de la question.
     *
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Retourne le QCM auquel appartient la question.
     *
     * @return Qcm|null
     */
    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    /**
     * Définit le QCM auquel appartient la question.
     *
     * @param Qcm|null $qcm
     * @return $this
     */
    public function setQcm(?Qcm $qcm): static
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Retourne la collection des réponses associées à la question.
     *
     * @return Collection<Response>
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    /**
     * Ajoute une réponse à la question.
     * Maintient la cohérence de la relation bidirectionnelle.
     *
     * @param Response $response
     * @return $this
     */
    public function addResponse(Response $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setQuestion($this);
        }
        return $this;
    }

    /**
     * Supprime une réponse de la question.
     *
     * @param Response $response
     * @return $this
     */
    public function removeResponse(Response $response): static
    {
        if ($this->responses->removeElement($response)) {
            if ($response->getQuestion() === $this) {
                $response->setQuestion(null);
            }
        }
        return $this;
    }

    /**
     * Définit l’ensemble des réponses de la question.
     * Réinitialise la collection et garantit la cohérence
     * des relations entre Question et Response.
     *
     * @param iterable $responses
     * @return $this
     */
    public function setResponses(iterable $responses): static
    {
        $this->responses = new ArrayCollection();

        foreach ($responses as $response) {
            $this->addResponse($response);
        }

        return $this;
    }
}
