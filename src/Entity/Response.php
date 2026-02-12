<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Get;
use App\Repository\ResponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResponseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['Response:read']],
    denormalizationContext: ['groups' => ['Response:write']]
)]
class Response
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Response:read', 'qcm:read', 'Question:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Response:read', 'Response:write', 'qcm:read', 'Question:read', 'qcm:write'])]
    private ?string $label = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Response:read', 'Response:write'])]
    private ?Question $question = null;

    #[ORM\Column]
    #[Groups(['Response:read', 'Response:write', 'qcm:read', 'Question:read', 'qcm:write'])]
    private ?bool $isCorrect = null;

    /**
     * Retourne l'identifiant de la réponse.
     *
     * @return int|null l'identifiant de la réponse ou null si non enregistrée
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le contenu de la réponse.
     *
     * @return string|null la réponse ou null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * Définit le contenu de la réponse.
     *
     * @param string $label la réponse
     * @return $this
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
