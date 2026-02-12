<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Le VideoController gère l’ajout de vidéos pédagogiques.
 * Il traite l’upload d’un fichier vidéo, vérifie son format,
 * enregistre le fichier sur le serveur et crée l’entité correspondante en base.
 */
class VideoController extends AbstractController
{
    /**
     * Traite l’ajout d’une nouvelle vidéo.
     * Si la requête est en POST, le fichier envoyé est récupéré,
     * vérifié (format MP4 uniquement), renommé avec un identifiant unique,
     * puis déplacé dans le dossier /public/uploads/videos.
     * Une entité Video est ensuite créée et persistée en base de données.
     * En cas d’échec ou de requête invalide, une page d’erreur est affichée.
     *
     * @param Request $request Requête HTTP contenant les données du formulaire
     * @param EntityManagerInterface $em Gestionnaire Doctrine pour la persistance
     * @return Response Redirection vers la liste des cours ou affichage d’erreur
     */
    #[Route('/videos/new', name: 'video_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {

            /** @var UploadedFile|null $videoFile */
            $videoFile = $request->files->get('video');

            if ($videoFile) {

                // Vérification mp4
                if ($videoFile->guessExtension() !== 'mp4') {
                    $this->addFlash('error', 'La vidéo doit être au format MP4.');
                    return $this->redirectToRoute('video_new');
                }

                // Nom unique
                $filename = uniqid() . '.mp4';

                // Déplacement
                $videoFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/videos',
                    $filename
                );

                $video = new Video();
                $video->setTitle($request->request->get('title'));
                $video->setLink('/uploads/videos/' . $filename);
                $video->setTeacher($this->getUser());
                $video->setDuration(0);
                $video->setDescription(null);

                $em->persist($video);
                $em->flush();

                return $this->redirectToRoute('course_index');
            }
        }

        return $this->render('error/index.html.twig');
    }
}
