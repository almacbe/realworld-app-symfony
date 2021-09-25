<?php

namespace App\Tests\ObjectMother;

use App\Entity\User;

final class UserMother
{
    public static function anUser(): User
    {
        $user = new User();
        $user
            ->setEmail('user@email.com')
            ->setUsername('username')
            ->setRoles(['ROLE_USER'])
            ->setPassword('$2y$13$kny98Em0E5hX.S5/uFUor.wUj1PkGx1xynVdRuzi6ZD7xwo.QRRri')
        ;

        return $user;
    }
}
