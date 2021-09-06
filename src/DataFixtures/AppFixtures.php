<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordHasherEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordEncoderInterface $encoder){}

    public function load(ObjectManager $manager)
    {

        $admin = new User();

        $hash = $this->encoder->encodePassword($admin,'password');

        $admin->setEmail("admin@mail.com")
            ->setPassword($hash)
            ->setFullName("Admin")
            ->setRoles(["ROLE_ADMIN"])
        ;

        $manager->persist($admin);

        for($u = 0; $u < 5; $u++){
            $user = new User();
            $user->setEmail("user$u@mail.com")
                ->setPassword($hash)
                ->setFullName("User $u");

            $manager->persist($user);
        }


        $manager->flush();
    }
}
