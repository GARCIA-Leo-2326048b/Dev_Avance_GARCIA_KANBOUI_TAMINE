<?php

namespace App\Controller;

use App\Entity\Qcm;
use App\Entity\Document;
use App\Repository\DocumentRepository;
use PhpParser\Comment\Doc;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class QcmController extends AbstractController
{
    #[Route('/qcm/generate', name: 'generate_qcm')]
    public function generateQCM(DocumentRepository $documentRepository, Request $request): Response
    {
        $documentLink = $request->query->get('documentLink');
        $document = $documentRepository->findOneByLink($documentLink);
        $qcm = $document->getQcm();

        if (!$qcm) {
            throw $this->createNotFoundException('QCM introuvable');
        }

        return $this->render('qcm/generate.html.twig', [
            'qcm' => $qcm
        ]);
    }
}
