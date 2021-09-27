<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController
{
    #[Route("/purchase/pay/{id}", name: "purchase_payement_form")]
    public function showCardForm($id, PurchaseRepository $purchaseRepository,StripeService $stripeService):Response
    {
        $purchase = $purchaseRepository->find($id);

        if(!$purchase || ($purchase->getUser() !== $this->getUser() || ($purchase->getStatus() === Purchase::STATUS_PAID))){
            return $this->redirectToRoute('cart_show');
        }

        $intent = $stripeService->getPayementIntent($purchase);

        return $this->render('purchase/payment.html.twig',[
            'clientSecret'=>$intent->client_secret,
            'purchase'=>$purchase,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}