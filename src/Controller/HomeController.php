<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Le HomeController gère la page d’accueil de l’application.
 * Il sert de point d’entrée principal et redirige l’utilisateur
 * en fonction de son état d’authentification.
 */
class HomeController extends AbstractController
{
    /**
     * Cette méthode correspond à la route racine "/".
     * Si aucun utilisateur n’est connecté, elle redirige vers la page de connexion.
     * Si un utilisateur est authentifié, elle le redirige vers la liste des cours.
     *
     * @return Response Redirection vers la page appropriée selon l’état de connexion
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('course_index');
    }
}
