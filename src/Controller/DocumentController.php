<?php

namespace App\Controller;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Le DocumentController gère l’ajout de documents pédagogiques.
 * Il s’occupe du traitement de l’upload d’un fichier PDF,
 * de son enregistrement physique sur le serveur,
 * ainsi que de la création de l’entité correspondante en base de données.
 */
class DocumentController extends AbstractController
{
    /**
     * Traite l’ajout d’un nouveau document.
     * Si la requête est en POST, le fichier envoyé est récupéré,
     * vérifié (format PDF uniquement), renommé avec un identifiant unique
     * puis déplacé dans le dossier /public/uploads/documents.
     * Une entité Document est ensuite créée et persistée en base.
     * En cas d’échec ou de requête non valide, une page d’erreur est affichée.
     *
     * @param Request $request Requête HTTP contenant les données du formulaire
     * @param EntityManagerInterface $em Gestionnaire Doctrine pour la persistance
     * @return Response Réponse HTTP après traitement ou affichage d’erreur
     */
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

        return $this->render('error/index.html.twig');
    }
}
