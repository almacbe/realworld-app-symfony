<?php

namespace App\DataFixtures;

use App\Tests\ObjectMother\UserMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(UserMother::aUser());
        $manager->flush();
    }
}
