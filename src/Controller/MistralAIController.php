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

/**
 * Le MistralAIController permet de générer automatiquement un QCM
 * à partir d’un document PDF existant. Il extrait le texte du document,
 * envoie son contenu à un service d’intelligence artificielle (Mistral),
 * récupère un QCM au format JSON puis crée les entités correspondantes
 * (Qcm, Question, Response) en base de données.
 */
class MistralAIController extends AbstractController
{
    /**
     * Génère un QCM à partir d’un document PDF.
     * La méthode récupère le lien du document via la requête,
     * localise le fichier sur le serveur, extrait son contenu texte,
     * construit un prompt pour MistralAI afin de produire un QCM structuré en JSON,
     * puis transforme ce JSON en entités persistées en base de données.
     * Une fois le QCM sauvegardé, l’utilisateur est redirigé vers la liste des cours.
     *
     * @param EntityManagerInterface $em Gestionnaire Doctrine pour la persistance
     * @param DocumentRepository $documentRepository Repository permettant de récupérer le document en base
     * @param Request $request Requête HTTP contenant le lien du document
     * @param MistralAIService $mistralAIService Service chargé d’interroger l’API Mistral
     * @param KernelInterface $kernel Permet d’accéder au répertoire racine du projet
     * @return Response Redirection vers la page des cours après génération
     * @throws \Exception En cas d’erreur de lecture du document ou de traitement du JSON
     */
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

        $projectDir = $kernel->getProjectDir();
        $publicDir = $projectDir . '/public';

        $relativePath = parse_url($documentUrl, PHP_URL_PATH);
        $filePath = $publicDir . $relativePath;

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException("Le fichier n'existe pas : " . $filePath);
        }

        $parser = new Parser();
        try {
            $pdf = $parser->parseFile($filePath);
            $extractedText = $pdf->getText();
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Impossible de lire le document : ' . $e->getMessage());
        }

        $prompt = "
Voici le contenu d'un document de cours :
---
" . $extractedText . "
---

### Instructions pour générer un QCM :
1. Génère un QCM de 5 questions basées uniquement sur le contenu ci-dessus.
2. Chaque question doit avoir 4 réponses possibles.
3. Indique la bonne réponse pour chaque question avec un champ isCorrect: true.
4. Ne modifie pas le champ 'document'.
5. Le format de sortie doit être un JSON valide respectant strictement la structure fournie.
";

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

        if (preg_match('#/api/documents/(\d+)#', $documentUrl, $matches)) {
            $documentId = (int) $matches[1];
            $document = $documentRepository->findById($documentId);

            if (!$document) {
                throw $this->createNotFoundException('Document introuvable avec l\'ID ' . $documentId);
            }

            $qcm->setDocument($document);
        } else {
            throw new \Exception('Impossible d\'extraire l\'ID du document depuis : ' . $documentUrl);
        }

        $em->persist($qcm);

        foreach ($data['questions'] as $q) {
            $question = new Question();
            $question->setQuestion($q['question']);
            $question->setQcm($qcm);
            $em->persist($question);

            foreach ($q['responses'] as $r) {
                $qcmResponse = new QcmResponse();
                $qcmResponse->setLabel($r['label']);
                $qcmResponse->setIsCorrect($r['isCorrect']);
                $qcmResponse->setQuestion($question);
                $em->persist($qcmResponse);
            }
        }

        $em->flush();
        return $this->redirectToRoute('course_index');
    }
}
