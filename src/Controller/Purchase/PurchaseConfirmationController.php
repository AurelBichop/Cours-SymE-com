<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use App\Form\CartConfirmationType;
use App\Purschase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseConfirmationController extends AbstractController
{
    #[
        Route('purchase/confirm',name:"purchase_confirm"),
        isGranted('ROLE_USER',message: 'vous devez être connecté')
    ]
    public function confirm(Request $request,CartService $cartService,EntityManagerInterface $entityManager, PurchasePersister $purchasePersister)
    {
        $form = $this->createForm(CartConfirmationType::class);
        $form->handleRequest($request);

        if(!$form->isSubmitted()){
            $this->addFlash('warning', 'Vous devez remplir le formulaire');
            return $this->redirectToRoute('cart_show');
        }

        $cartItems = $cartService->getDetailCartItems();

        if(count($cartItems) === 0){
            $this->addFlash('warning',"Vous ne pouvez confirmer une commande avec un panier vide");
            return $this->redirectToRoute('cart_show');
        }

        /** @var Purchase $purchase */
        $purchase = $form->getData();

        $purchasePersister->storePurchase($purchase);

        return $this->redirectToRoute("purchase_payement_form",[
            'id'=>$purchase->getId()
        ]);
    }
}