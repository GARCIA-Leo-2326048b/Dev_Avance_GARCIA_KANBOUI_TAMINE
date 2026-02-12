<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\QuizzAttemptRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * L'entité QuizzAttempt représente une tentative réalisée par un étudiant
 * sur un QCM donné. Elle permet d’enregistrer l’utilisateur ayant effectué
 * le test, le QCM concerné ainsi que la note obtenue.
 * Cette entité est utilisée pour conserver l’historique des évaluations
 * et permettre l’affichage des résultats.
 */
#[ORM\Entity(repositoryClass: QuizzAttemptRepository::class)]
#[ApiResource]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['QuizzAttemp:read']],
    denormalizationContext: ['groups' => ['QuizzAttemp:write']]
)]
class QuizzAttempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['QuizzAttemp:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['QuizzAttemp:read', 'QuizzAttemp:write'])]
    private ?User $student = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['QuizzAttemp:read', 'QuizzAttemp:write'])]
    private ?Qcm $qcm = null;

    #[ORM\Column]
    #[Groups(['QuizzAttemp:read', 'QuizzAttemp:write'])]
    private ?float $grade = null;

    /**
     * Retourne l'identifiant unique de la tentative.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l’étudiant ayant effectué la tentative.
     *
     * @return User|null
     */
    public function getStudent(): ?User
    {
        return $this->student;
    }

    /**
     * Définit l’étudiant associé à cette tentative.
     *
     * @param User|null $student
     * @return $this
     */
    public function setStudent(?User $student): static
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Retourne le QCM concerné par la tentative.
     *
     * @return Qcm|null
     */
    public function getQcm(): ?Qcm
    {
        return $this->qcm;
    }

    /**
     * Définit le QCM associé à cette tentative.
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
     * Retourne la note obtenue lors de la tentative.
     *
     * @return float|null
     */
    public function getGrade(): ?float
    {
        return $this->grade;
    }

    /**
     * Définit la note obtenue lors de la tentative.
     *
     * @param float $grade
     * @return $this
     */
    public function setGrade(float $grade): static
    {
        $this->grade = $grade;

        return $this;
    }
}
