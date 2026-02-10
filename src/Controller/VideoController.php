<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VideoController extends AbstractController
{
    #[Route('/videos/new', name: 'video_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {

            /** @var UploadedFile|null $videoFile */
            $videoFile = $request->files->get('video');

            if ($videoFile) {

                // Sécurité basique : MP4 uniquement
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

                // Entité
                $video = new Video();
                $video->setTitle($request->request->get('title'));
                $video->setLink('/uploads/videos/' . $filename);
                $video->setTeacher($this->getUser());
                $video->setDuration(0); // calculable plus tard
                $video->setDescription(null);

                // Sauvegarde
                $em->persist($video);
                $em->flush();

                return $this->redirectToRoute('course_index');
            }
        }

        return $this->render('home/index.html.twig');
    }
}
