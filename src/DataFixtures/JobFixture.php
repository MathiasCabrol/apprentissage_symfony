<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Architecte",
            "Agriculteur",
            "Banquier",
            "Biologiste",
            "Automaticien",
            "Décolleteur",
            "Charpentier",
            "Designer produit",
            "Fiabiliste maintenance",
            "Electromécanicien",
        ];

        for($i = 0; $i < count($data); $i++) {
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }

        $manager->flush();
    }
}
