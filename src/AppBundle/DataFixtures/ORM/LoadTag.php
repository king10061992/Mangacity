<?php
// src/AppBundle/DataFixtures/ORM/LoadProduct.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;


class LoadTag implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de tag à ajouter
        $names = array(
            'Bijoux & Costume',
            'Coque pour smartphone',
            'Dakimakura ',
            'Figurine',
            'Goodies',
            'Jeux video',
            'Jeux de société',
            'Peluches',
            'Poster / Tableau',
            'Comics',
            'Manga',
            'Bande dessinee',
            'Nourriture & bonbon',
            'Mug / Bol / Chope',
            'Porte-clés',
            'Portefeuilles',
            'Stickers Muraux',
            'Tapis de souris'
        );

        foreach ($names as $name) {
            // On crée les tag
            $product = new product();
            $product->setName($name);
            $manager->persist($product);
            $manager->flush();
        }

        // On déclenche l'enregistrement de toutes les tag

    }
}