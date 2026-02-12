<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Le SecurityController gère l’authentification des utilisateurs.
 * Il permet l’affichage du formulaire de connexion et la gestion
 * de la déconnexion via le système de sécurité de Symfony.
 */
class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion.
     * La méthode récupère la dernière erreur d’authentification éventuelle
     * ainsi que le dernier identifiant saisi par l’utilisateur afin de
     * préremplir le formulaire et afficher un message d’erreur si nécessaire.
     *
     * @param AuthenticationUtils $authenticationUtils Service permettant de récupérer les informations d’authentification
     * @return Response Page de connexion
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Gère la déconnexion de l’utilisateur.
     * Cette méthode n’est jamais exécutée directement : elle est interceptée
     * par le système de sécurité Symfony via la configuration du firewall.
     *
     * @return void
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
