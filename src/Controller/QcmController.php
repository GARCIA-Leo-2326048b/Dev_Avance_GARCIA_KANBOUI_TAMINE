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
    public function generateQCM(
        DocumentRepository $documentRepository,
        Request $request
    ): Response {
        $documentLink = $request->query->get('documentLink');
        $document = $documentRepository->findOneByLink($documentLink);
        $qcm = $document->getQcm();

        if (!$qcm) {
            throw $this->createNotFoundException('QCM introuvable');
        }

        // Si soumission du formulaire
        if ($request->isMethod('POST')) {

            $score = 0;
            $total = 0;

            foreach ($qcm->getQuestions() as $question) {

                $total++;
                $selectedResponseId = $request->request->get('question_' . $question->getId());

                foreach ($question->getResponses() as $response) {

                    if (
                        $response->getId() == $selectedResponseId &&
                        $response->getIsCorrect()
                    ) {
                        $score++;
                    }
                }
            }

            return $this->render('qcm/result.html.twig', [
                'qcm' => $qcm,
                'score' => $score,
                'total' => $total
            ]);
        }

        return $this->render('qcm/generate.html.twig', [
            'qcm' => $qcm
        ]);
    }
}
