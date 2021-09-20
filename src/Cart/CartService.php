<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    public function __construct(
        private SessionInterface $session,
        private ProductRepository $productRepository
    ){}

    protected function getCart(){
        return $this->session->get('cart',[]);
    }

    protected function saveCart(array $cart){
        $this->session->set('cart',$cart);
    }

    public function add(int $id)
    {
        $cart = $this->getCart();

        if(!array_key_exists($id,$cart)){
            $cart[$id]=0;
        }

        $cart[$id]++;

        $this->saveCart($cart);
    }

    public function getTotal():int
    {
        $total = 0;

        foreach ($this->session->get('cart',[]) as $id=>$qty){
            $product = $this->productRepository->find($id);

            if(!$product){
                continue;
            }

            $total += $product->getPrice()*$qty;
        }
        return $total;
    }


    public function getDetailCartItems():array
    {
        $detailedCart = [];

        foreach ($this->getCart() as $id=>$qty){
            $product = $this->productRepository->find($id);

            if(!$product){
                continue;
            }

            $detailedCart[] = new CartItem($product, $qty);

        }
        return $detailedCart;
    }

    public function remove($id){
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    public function decrement($id){
        $cart = $this->getCart();

        if (!array_key_exists($id,$cart)){
            return;
        }

        if($cart[$id] === 1){
            $this->remove($id);
        }else{
            $cart[$id]--;
            $this->saveCart($cart);
        }
    }

    public function empty(){
        $this->saveCart([]);
    }
}