<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected CartService $cartService
    ){}


    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id,Request $request)
    {
        $product = $this->productRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->cartService->add($id);

        $this->addFlash('success','produit Ajouté');


        if ($request->query->get('returnToCart')){
            return $this->redirectToRoute("cart_show");
        }

        return $this->redirectToRoute("product_details",[
            "category_slug"=>$product->getCategory()->getSlug(),
            "slug"=>$product->getSlug()
        ]);
    }

    #[Route('/cart', name:"cart_show")]
    public function show(CartService $cartService):Response
    {
        $detailedCart = $cartService->getDetailCartItems();
        $total = $cartService->getTotal();

        $form = $this->createForm(CartConfirmationType::class);

        return $this->render('cart/index.html.twig',[
            'items'=>$detailedCart,
            'total'=>$total,
            'form'=> $form->createView()
        ]);
    }

    #[Route('/cart/delete/{id}', name: 'cart_delete',requirements: ['id' => '\d+'])]
    public function delete($id): RedirectResponse
    {
        $product = $this->productRepository->find($id);

        if(!$product){
            $this->createNotFoundException("le produit $id n'existe Pas !");
        }

        $this->cartService->remove($id);

        $this->addFlash("success","produit supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }

    #[Route('/cart/decrement/{id}', name: 'cart_decrement',requirements: ['id' => '\d+'])]
    public function decrement($id):Response
    {
        $product = $this->productRepository->find($id);
        if(!$product){
            $this->createNotFoundException("le produit $id n'existe Pas !");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success',"retrait du produit réussi");

        return $this->redirectToRoute("cart_show");
    }
}
