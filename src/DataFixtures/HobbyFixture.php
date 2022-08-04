<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Jardinage",
            "Elevage d'animaux",
            "Peinture",
            "Sculpture",
            "Photographie",
            "Décoration d'intérieur",
            "Ecriture créative",
            "Chanter",
            "Jeux vidéo",
            "Arts martiaux",
        ];

        for($i = 0; $i < count($data); $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }

        $manager->flush();
    }
}
