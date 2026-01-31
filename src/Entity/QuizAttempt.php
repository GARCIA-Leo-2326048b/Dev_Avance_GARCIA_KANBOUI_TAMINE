<?php

namespace App\Entity;

use App\Repository\QuizAttemptRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizAttemptRepository::class)]
class QuizAttempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $times = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimes(): ?int
    {
        return $this->times;
    }

    public function setTimes(int $times): static
    {
        $this->times = $times;

        return $this;
    }
}
