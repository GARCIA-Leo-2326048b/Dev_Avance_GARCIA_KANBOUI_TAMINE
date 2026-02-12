<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * La classe AppFixtures permet de charger des données initiales
 * dans la base de données à des fins de développement ou de test.
 * Elle est utilisée avec le bundle DoctrineFixturesBundle.
 */
class AppFixtures extends Fixture
{
    /**
     * Méthode appelée lors de l’exécution des fixtures.
     * Elle permet de créer et persister des entités en base
     * afin de préremplir l’application avec des données de test.
     *
     * @param ObjectManager $manager Gestionnaire Doctrine pour la persistance des entités
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
