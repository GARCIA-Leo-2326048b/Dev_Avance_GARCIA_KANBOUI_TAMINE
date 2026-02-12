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

/**
 * Le QcmController gère l’affichage d’un QCM associé à un document.
 * Il permet de récupérer un document via son lien et d’accéder
 * au QCM qui lui est rattaché afin de l’afficher à l’utilisateur.
 */
class QcmController extends AbstractController
{
    /**
     * Cette méthode génère l’affichage d’un QCM à partir d’un document.
     * Elle récupère le paramètre "documentLink" dans la requête,
     * recherche le document correspondant en base de données,
     * puis obtient le QCM associé à ce document.
     * Si aucun QCM n’est trouvé, une exception est levée.
     * Sinon, le QCM est transmis au template Twig pour affichage.
     *
     * @param DocumentRepository $documentRepository Repository permettant de récupérer le document en base
     * @param Request $request Requête HTTP contenant le lien du document
     * @return Response Page affichant le QCM
     */
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
