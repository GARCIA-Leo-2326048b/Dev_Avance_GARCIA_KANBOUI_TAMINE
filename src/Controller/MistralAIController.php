<?php
namespace App\Controller;

use App\Entity\Qcm;
use App\Entity\Question;
use App\Entity\Response as QcmResponse;
use App\Repository\DocumentRepository;
use App\Service\MistralAIService;
use Doctrine\ORM\EntityManagerInterface;
use Smalot\PdfParser\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

class MistralAIController extends AbstractController
{
    #[Route('/QCM/Document', name: 'qcm_document')]
    public function qcmDocument(
        EntityManagerInterface $em,
        DocumentRepository $documentRepository,
        Request $request,
        MistralAIService $mistralAIService,
        KernelInterface $kernel
    ): Response {
        $documentUrl = $request->query->get('documentLink');
        if (!$documentUrl) {
            throw $this->createNotFoundException('Aucun lien de document fourni.');
        }

        $id = $documentRepository->findIdByLink($documentUrl);

        // Convertir l'URL en chemin local
        $projectDir = $kernel->getProjectDir();
        $publicDir = $projectDir . '/public';

        // Extraire le chemin relatif depuis l'URL
        $relativePath = parse_url($documentUrl, PHP_URL_PATH);

        // Construire le chemin absolu sur le serveur
        $filePath = $publicDir . $relativePath;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException("Le fichier n'existe pas : " . $filePath);
        }

        // Extraire le texte du PDF
        $parser = new Parser();
        try {
            $pdf = $parser->parseFile($filePath);
            $extractedText = $pdf->getText();
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Impossible de lire le document : ' . $e->getMessage());
        }

        // Générer le QCM
        $prompt = "
Voici le contenu d'un document de cours :
---
" . $extractedText . "
---

### Instructions pour générer un QCM :
1. **Génère un QCM de 5 questions** basées uniquement sur le contenu ci-dessus.
2. **Chaque question** doit avoir **4 réponses possibles**.
3. **Indique la bonne réponse** pour chaque question avec un champ `isCorrect: true`.
4. **Ne modifie pas le champ 'document'.
5. **Le format de sortie doit être un JSON valide** respectant strictement la structure suivante:

{
  \"title\": \"Titre du QCM basé sur le document\",
  \"description\": \"Description du QCM\",
  \"document\": \"/api/documents/$id\",
  \"questions\": [
    {
      \"question\": \"Texte de la question 1\",
      \"responses\": [
        { \"label\": \"Réponse A\", \"isCorrect\": false },
        { \"label\": \"Réponse B\", \"isCorrect\": true },
        { \"label\": \"Réponse C\", \"isCorrect\": false },
        { \"label\": \"Réponse D\", \"isCorrect\": false }
      ]
    },
    {
      \"question\": \"Texte de la question 2\",
      \"responses\": [
        { \"label\": \"Réponse A\", \"isCorrect\": false },
        { \"label\": \"Réponse B\", \"isCorrect\": false },
        { \"label\": \"Réponse C\", \"isCorrect\": true },
        { \"label\": \"Réponse D\", \"isCorrect\": false }
      ]
    }
  ]
}";

        $mistralResponse = $mistralAIService->askMistral($prompt);
        $qcmJson = $mistralResponse['choices'][0]['message']['content'];

        $qcmJson = trim($qcmJson);
        $qcmJson = preg_replace('/^```json/', '', $qcmJson);
        $qcmJson = preg_replace('/```$/', '', $qcmJson);
        $qcmJson = trim($qcmJson);

        $data = json_decode($qcmJson, true);

        $qcm = new Qcm();
        $qcm->setTitle($data['title']);
        $qcm->setDescription($data['description']);
        $documentUrl = $data['document'];

        // Extraire l'ID avec une regex
        if (preg_match('#/api/documents/(\d+)#', $documentUrl, $matches)) {
            $documentId = (int) $matches[1];

            // Récupérer l'entité Document depuis le repository
            $document = $documentRepository->findById($documentId);

            if (!$document) {
                throw $this->createNotFoundException('Document introuvable avec l\'ID ' . $documentId);
            }

            $qcm->setDocument($document);
        } else {
            throw new \Exception('Impossible d\'extraire l\'ID du document depuis : ' . $documentUrl);
        }

        $em->persist($qcm);

        //Boucle sur les questions
        foreach ($data['questions'] as $q) {
            $question = new Question();
            $question->setQuestion($q['question']);
            $question->setQcm($qcm);
            $em->persist($question);

            //Boucle sur les réponses
            foreach ($q['responses'] as $r) {
                $qcmResponse = new QcmResponse();
                $qcmResponse->setLabel($r['label']);
                $qcmResponse->setIsCorrect($r['isCorrect']);
                $qcmResponse->setQuestion($question);
                $em->persist($qcmResponse);
            }
        }

        //Sauvegarde en base
        $em->flush();
        return $this->redirectToRoute('course_index');
    }
}
