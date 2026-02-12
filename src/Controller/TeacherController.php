<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Le TeacherController gère l’espace dédié aux enseignants.
 * Il permet d’afficher le tableau de bord professeur depuis lequel
 * les contenus pédagogiques et les évaluations peuvent être administrés.
 */
class TeacherController extends AbstractController
{
    /**
     * Affiche le tableau de bord enseignant.
     * Cette méthode retourne la vue principale de l’espace professeur,
     * qui centralise les actions liées à la gestion des cours et des QCM.
     *
     * @return Response Page du tableau de bord enseignant
     */
    #[Route('/teacher', name: 'teacher_dashboard')]
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig');
    }
}
