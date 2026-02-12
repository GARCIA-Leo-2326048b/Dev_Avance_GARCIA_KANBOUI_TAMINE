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

/**
 * Le QuizzAttemptController gère les tentatives de QCM des étudiants.
 * Il permet d’afficher la liste des tentatives enregistrées
 * et de traiter la soumission d’un QCM afin de calculer et sauvegarder la note.
 */
class QuizzAttemptController extends AbstractController
{
    /**
     * Affiche la liste complète des tentatives de QCM enregistrées en base.
     * Cette méthode récupère toutes les entités QuizzAttempt
     * et les transmet au template Twig pour affichage.
     *
     * @param QuizzAttemptRepository $quizzAttemptRepository Repository des tentatives de QCM
     * @return Response Page listant les tentatives
     */
    #[Route('/quizz/attempt/list', name: 'quizz_attempt_list')]
    public function listQuizzAttempt(QuizzAttemptRepository $quizzAttemptRepository)
    {
        $attempts = $quizzAttemptRepository->findAll();

        return $this->render('quizz_attempt/list.html.twig', [
            'attempts' => $attempts
        ]);
    }

    /**
     * Traite la soumission d’un QCM par un étudiant.
     * Elle récupère l’identifiant du QCM et les réponses envoyées,
     * vérifie les réponses correctes, calcule la note sur 20,
     * crée une nouvelle entité QuizzAttempt puis l’enregistre en base.
     * Enfin, elle affiche la page de résultat avec le score obtenu.
     *
     * @param Request $request Requête HTTP contenant les réponses du formulaire
     * @param QcmRepository $qcmRepository Repository permettant de récupérer le QCM
     * @param ResponseRepository $responseRepository Repository des réponses pour vérifier la correction
     * @param EntityManagerInterface $em Gestionnaire Doctrine pour la persistance
     * @return Response Page affichant le résultat du QCM
     */
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

        $grade = ($correctAnswers / $totalQuestions) * 20;

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
