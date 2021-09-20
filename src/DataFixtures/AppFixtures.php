<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
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
        $users[] = $admin;
        for($u = 0; $u < 5; $u++){
            $user = new User();
            $user->setEmail("user$u@mail.com")
                ->setPassword($hash)
                ->setFullName("User $u");

            $users[] = $user;

            $manager->persist($user);
        }


        $categories = [];
        for ($c=0;$c < 4;$c++){
            $category = new Category();
            $category ->setName("Categorie$c")
                      ->setSlug("categorie$c")
                      ->setOwner($users[mt_rand(0,5)]);
            $manager->persist($category);

            $categories[] = $category;
        }

        $products = [];
        for($p=0;$p < 10; $p++){
            $product = new Product();
            $product->setName("Product$p")
            ->setMainPicture("https://via.placeholder.com/300")
            ->setSlug("product$p")
            ->setShortDescription("lreomenfke fj erjf erfj rejihjfb erfurefb")
            ->setPrice(mt_rand(1000,100000))
            ->setCategory($categories[mt_rand(0,3)]);

            $manager->persist($product);

            $products[] = $product;
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

            $selectedProducts = [];
            for ($m=0;$m < mt_rand(0,10);$m++){
                $selectedProducts[] = $products[mt_rand(0,9)];
            }

            foreach ($selectedProducts as $product){
                $purchaseItem = new PurchaseItem();
                $purchaseItem
                    ->setProduct($product)
                    ->setQuantity(mt_rand(1,3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal(
                        $purchaseItem->getQuantity() * $purchaseItem->getProductPrice()
                    )
                    ->setPurchase($purchase);

                $manager->persist($purchaseItem);
            }

            if(mt_rand(0,1)){
                $purchase->setStatus(Purchase::STATUS_PAID);
            }


            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
