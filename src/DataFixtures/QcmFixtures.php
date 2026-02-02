<?php

namespace App\DataFixtures;

use App\Entity\Qcm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QcmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $qcmsData = [
            [
                'title' => 'PHP - Bases',
                'description' => 'Questions sur les bases du langage PHP',
            ],
            [
                'title' => 'Symfony',
                'description' => 'QCM sur le framework Symfony',
            ],
            [
                'title' => 'HTML / CSS',
                'description' => 'Notions essentielles du web',
            ],
            [
                'title' => 'JavaScript',
                'description' => 'QCM JavaScript niveau débutant',
            ],
            [
                'title' => 'SQL',
                'description' => 'Requêtes SQL et bases de données',
            ],
        ];

        foreach ($qcmsData as $data) {
            $qcm = new Qcm();
            $qcm->setTitle($data['title']);
            $qcm->setDescription($data['description']);

            $manager->persist($qcm);
        }

        $manager->flush();
    }
}
