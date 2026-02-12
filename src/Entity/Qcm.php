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
use App\Entity\Question;

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
    normalizationContext: ['groups' => ['qcm:read']],
    denormalizationContext: ['groups' => ['qcm:write']]
)]
class Qcm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['qcm:read', 'Document:read', 'Video:read', 'Question:read', 'QuizzAttemp:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Groups(['qcm:read', 'qcm:write', 'Document:read', 'Video:read', 'Question:read', 'QuizzAttemp:read'])]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['qcm:read', 'qcm:write'])]
    private ?string $description = null;

    #[ORM\OneToOne]
    #[Groups(['qcm:read', 'qcm:write'])]
    private ?Document $document = null;

    #[ORM\OneToOne]
    #[Groups(['qcm:read', 'qcm:write'])]
    private ?Video $video = null;

    #[ORM\OneToMany(mappedBy: 'qcm', targetEntity: Question::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['qcm:read', 'qcm:write'])]
    private Collection $questions;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
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

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(?Video $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQcm($this); // important : met à jour le côté owning
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            if ($question->getQcm() === $this) {
                $question->setQcm(null);
            }
        }

        return $this;
    }

    public function setQuestions(iterable $questions): static
    {
        $this->questions = new ArrayCollection();

        foreach ($questions as $question) {
            $this->addQuestion($question); // force setQcm()
        }

        return $this;
    }




}
