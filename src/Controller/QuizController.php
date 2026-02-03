<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    #[Route('/quiz', name: 'quiz_index')]
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig');
    }

    #[Route('/quiz/results', name: 'quiz_results')]
    public function results(): Response
    {
        return $this->render('quiz/results.html.twig');
    }
}
