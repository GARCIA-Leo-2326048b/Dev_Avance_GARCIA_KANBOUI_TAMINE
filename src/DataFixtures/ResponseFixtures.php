<?php

namespace App\DataFixtures;

use App\Entity\Qcm;
use App\Entity\Response;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResponseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $responsesData = [
            // PHP
            [
                'question' => 'À quoi sert PHP ?',
                'response' => 'Créer des pages web dynamiques côté serveur',
                'qcm' => 'qcm_0',
            ],
            [
                'question' => 'Comment déclarer une variable ?',
                'response' => 'Avec le symbole $',
                'qcm' => 'qcm_0',
            ],

            // Symfony
            [
                'question' => 'Symfony est un…',
                'response' => 'Framework PHP',
                'qcm' => 'qcm_1',
            ],
            [
                'question' => 'Quel ORM utilise Symfony ?',
                'response' => 'Doctrine',
                'qcm' => 'qcm_1',
            ],

            // HTML / CSS
            [
                'question' => 'HTML sert à…',
                'response' => 'Structurer une page web',
                'qcm' => 'qcm_2',
            ],
            [
                'question' => 'CSS permet de…',
                'response' => 'Mettre en forme une page web',
                'qcm' => 'qcm_2',
            ],

            // JavaScript
            [
                'question' => 'JavaScript s’exécute…',
                'response' => 'Dans le navigateur',
                'qcm' => 'qcm_3',
            ],
            [
                'question' => 'Quel mot-clé pour une constante ?',
                'response' => 'const',
                'qcm' => 'qcm_3',
            ],

            // SQL
            [
                'question' => 'Quelle commande lit des données ?',
                'response' => 'SELECT',
                'qcm' => 'qcm_4',
            ],
            [
                'question' => 'Quelle clause filtre les résultats ?',
                'response' => 'WHERE',
                'qcm' => 'qcm_4',
            ],
        ];

        foreach ($responsesData as $data) {
            $response = new Response();
            $response
                ->setQuestion($data['question'])
                ->setResponse($data['response'])
                ->setQcm($this->getReference($data['qcm'], Qcm::class));

            $manager->persist($response);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QcmFixtures::class,
        ];
    }
}
