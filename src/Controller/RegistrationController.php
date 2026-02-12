<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Le RegistrationController gère l’inscription des nouveaux utilisateurs.
 * Il permet la création d’un compte, l’attribution d’un rôle
 * (enseignant ou étudiant) et l’enregistrement sécurisé du mot de passe.
 */
class RegistrationController extends AbstractController
{
    /**
     * Gère le processus d’inscription.
     * La méthode crée un formulaire basé sur RegistrationFormType,
     * traite les données envoyées, attribue un rôle selon le type d’utilisateur choisi,
     * chiffre le mot de passe à l’aide du UserPasswordHasher,
     * puis enregistre le nouvel utilisateur en base de données.
     * Après une inscription réussie, l’utilisateur est redirigé vers la page de connexion.
     *
     * @param Request $request Requête HTTP contenant les données du formulaire
     * @param UserPasswordHasherInterface $userPasswordHasher Service de hachage du mot de passe
     * @param EntityManagerInterface $entityManager Gestionnaire Doctrine pour la persistance
     * @return Response Page d’inscription ou redirection vers la connexion
     */
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userType = $form->get('userType')->getData();

            if ($userType === 'teacher') {
                $user->setRoles(['ROLE_TEACHER']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }

            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
