<?php

namespace App\Controller;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentController extends AbstractController
{
    #[Route('/documents/new', name: 'document_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {

            /** @var UploadedFile|null $documentFile */
            $documentFile = $request->files->get('document');

            if ($documentFile) {

                // Sécurité basique : PDF uniquement
                if ($documentFile->guessExtension() !== 'pdf') {
                    $this->addFlash('error', 'Le document doit être un PDF.');
                    return $this->redirectToRoute('document_new');
                }

                // Nom unique
                $filename = uniqid() . '.pdf';

                // Déplacement
                $documentFile->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/documents',
                    $filename
                );

                // Entité
                $document = new Document();
                $document->setTitle($request->request->get('title'));
                $document->setLink('/uploads/documents/' . $filename);
                $document->setTeacher($this->getUser());
                $document->setPages(0); // à calculer plus tard
                $document->setDescription(null);

                // Sauvegarde
                $em->persist($document);
                $em->flush();

                return $this->redirectToRoute('course_index');
            }
        }

        return $this->render('home/index.html.twig');
    }
}
