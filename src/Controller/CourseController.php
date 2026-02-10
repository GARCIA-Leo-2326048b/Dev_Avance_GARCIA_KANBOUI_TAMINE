<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
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
