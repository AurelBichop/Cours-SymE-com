<?php

namespace App\DataFixtures;

use App\Entity\Purchase;
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

        $users = [];
        for($u = 0; $u < 5; $u++){
            $user = new User();
            $user->setEmail("user$u@mail.com")
                ->setPassword($hash)
                ->setFullName("User $u");

            $users[] = $user;

            $manager->persist($user);
        }

        for($p = 0; $p < mt_rand(20,40); $p++){
            $purchase = new Purchase();

            $purchase->setFullName("Commande$p")
                    ->setAddress("street$p")
                    ->setPostalCode("66-$p")
                    ->setCity("Perpig-$p")
                    ->setUser($users[mt_rand(0,4)])
                    ->setTotal(mt_rand(2000,30000))
                    ->setPurchasedAt(new \DateTimeImmutable());

            if(mt_rand(0,1)){
                $purchase->setStatus(Purchase::STATUS_PAID);
            }
            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
