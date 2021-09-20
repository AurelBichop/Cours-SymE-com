<?php

namespace App\Purschase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{
    public function __construct(
        private Security $security,
        protected CartService $cartService,
        protected EntityManagerInterface $entityManager,

    )
    {
    }

    public function storePurchase(Purchase $purchase){

        /** @var User $user */
        $user = $this->security->getUser();
        $purchase->setUser($user)
            ->setPurchasedAt(new \DateTimeImmutable())
            ->setTotal($this->cartService->getTotal());

        $this->entityManager->persist($purchase);


        foreach ($this->cartService->getDetailCartItems() as $cartItem){
            $purchaseItem = (new PurchaseItem())
                ->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setQuantity($cartItem->qty)
                ->setProductName($cartItem->product->getName())
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->product->getPrice());

            $this->entityManager->persist($purchaseItem);
        }

        $this->entityManager->flush();
    }
}