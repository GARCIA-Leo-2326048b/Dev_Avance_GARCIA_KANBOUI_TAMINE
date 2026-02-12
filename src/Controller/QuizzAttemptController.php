<?php

namespace App\Controller;

use App\Entity\QuizzAttempt;
use App\Repository\QcmRepository;
use App\Repository\QuizzAttemptRepository;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class QuizzAttemptController extends AbstractController
{
    #[Route('/quizz/attempt/list', name: 'quizz_attempt_list')]
    public function listQuizzAttempt(QuizzAttemptRepository $quizzAttemptRepository)
    {
        $attempts = $quizzAttemptRepository->findAll();

        return $this->render('quizz_attempt/list.html.twig', [
            'attempts' => $attempts
        ]);
    }

    #[Route('/quizz/attempt/result', name: 'quizz_attempt_result', methods: ['POST'])]
    public function quizzAttemptResult(
        Request $request,
        QcmRepository $qcmRepository,
        ResponseRepository $responseRepository,
        EntityManagerInterface $em
    ): Response
    {
        $qcmId = $request->request->get('qcm_id');
        $answers = $request->request->all('answers');

        $qcm = $qcmRepository->find($qcmId);

        if (!$qcm) {
            throw $this->createNotFoundException('QCM not found');
        }

        $totalQuestions = count($qcm->getQuestions());
        $correctAnswers = 0;

        foreach ($answers as $questionId => $responseId) {

            $response = $responseRepository->find($responseId);

            if ($response && $response->getIsCorrect()) {
                $correctAnswers++;
            }
        }

        // Calcul note sur 20
        $grade = ($correctAnswers / $totalQuestions) * 20;

        // Création tentative
        $attempt = new QuizzAttempt();
        $attempt->setStudent($this->getUser());
        $attempt->setQcm($qcm);
        $attempt->setGrade($grade);

        $em->persist($attempt);
        $em->flush();

        return $this->render('quizz_attempt/result.html.twig', [
            'grade' => $grade,
            'total' => $totalQuestions,
            'correct' => $correctAnswers
        ]);
    }
}