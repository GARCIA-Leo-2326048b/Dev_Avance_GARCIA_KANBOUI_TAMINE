<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Le CourseController gère l’affichage des contenus pédagogiques
 * (documents et vidéos) ainsi que l’ajout de nouveaux fichiers.
 * Il centralise la logique liée aux cours dans l’application.
 */
class CourseController extends AbstractController
{
    /**
     * Affiche la liste des cours disponibles.
     * Cette méthode récupère tous les documents et vidéos via leurs repositories
     * puis transmet ces données au template Twig afin de les afficher.
     *
     * @param DocumentRepository $documentRepository Repository permettant d'accéder aux documents en base
     * @param VideoRepository $videoRepository Repository permettant d'accéder aux vidéos en base
     * @return Response Réponse HTTP contenant la page des cours
     */
    #[Route('/courses', name: 'course_index')]
    public function index(
        DocumentRepository $documentRepository,
        VideoRepository $videoRepository
    ): Response
    {
        return $this->render('course/index.html.twig', [
            'documents' => $documentRepository->findAll(),
            'videos' => $videoRepository->findAll(),
        ]);
    }

    /**
     * Gère la création d’un nouveau contenu pédagogique.
     * Si la requête est en POST, les fichiers envoyés sont récupérés,
     * renommés avec un identifiant unique puis déplacés dans les dossiers
     * publics correspondants. Après traitement, l’utilisateur est redirigé
     * vers la liste des cours. En GET, le formulaire d’ajout est affiché.
     *
     * @param Request $request Requête HTTP contenant les données du formulaire
     * @return Response Réponse HTTP affichant le formulaire ou redirigeant après traitement
     */
    #[Route('/courses/new', name: 'course_new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {

            $video = $request->files->get('video');
            $document = $request->files->get('document');

            if ($video) {
                $videoName = uniqid() . '.' . $video->guessExtension();
                $video->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/videos',
                    $videoName
                );
            }

            if ($document) {
                $docName = uniqid() . '.' . $document->guessExtension();
                $document->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/documents',
                    $docName
                );
            }

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig');
    }
}
