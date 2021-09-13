<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add($id,ProductRepository $productRepository,SessionInterface $session)
    {
        $product = $productRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $cart = $session->get('cart',[]);

        if(array_key_exists($id,$cart)){
            $cart[$id]++;
        }else{
            $cart[$id]=1;
        }

        $session->set('cart',$cart);

        $this->addFlash('success','produit Ajouté');

        return $this->redirectToRoute("product_details",[
            "category_slug"=>$product->getCategory()->getSlug(),
            "slug"=>$product->getSlug()
        ]);
    }

    #[Route('/cart', name:"cart_show")]
    public function show(SessionInterface $session,ProductRepository $productRepository)
    {
        $detailedCart = [];
        $total = 0;

        foreach ($session->get('cart',[]) as $id=>$qty){
            $product = $productRepository->find($id);

            $detailedCart[] = [
                'product'=> $product,
                'qty' => $qty
            ];

            $total += ($product->getPrice()*$qty);
        }

        return $this->render('cart/index.html.twig',[
            'items'=>$detailedCart,
            'total'=>$total
        ]);
    }
}
