<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $profile1 = new Profile();
        $profile1->setRs('Facebook');
        $profile1->setUrl('https://www.facebook.com/mathi.cabrol');
        
        $profile2 = new Profile();
        $profile2->setRs('Instagram');
        $profile2->setUrl('https://www.instagram.com/mathi_cabrol/');

        $profile3 = new Profile();
        $profile3->setRs('GitHub');
        $profile3->setUrl('https://github.com/MathiasCabrol');
        
        $manager->persist($profile1);
        $manager->persist($profile2);
        $manager->persist($profile3);

        $manager->flush();
    }
}
