<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentSuccessController extends AbstractController
{
    #[
        Route('/purchase/terminate/{id}', name: "purchase_payment_success"),
        IsGranted("ROLE_USER")
    ]
    public function success($id,PurchaseRepository $purchaseRepository,CartService $cartService, EntityManagerInterface $entityManager){
        $purchase = $purchaseRepository->find($id);

        if(!$purchase || ($purchase->getUser() !== $this->getUser() || ($purchase->getStatus() === Purchase::STATUS_PAID))){
            $this->addFlash('warning', "la commande n'existe pas");
            return $this->redirectToRoute("purchases_index");
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $entityManager->flush();

        $cartService->empty();

        $this->addFlash("success",'La commande est confirmé');
        return $this->redirectToRoute("purchases_index");
    }
}