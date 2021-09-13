<?php

namespace App\Cart;

use App\Entity\Product;

class CartItem
{
    public function __construct(
        public Product $product,
        public int $qty
    ){}

    public function getTotal():int
    {
        return $this->product->getPrice() * $this->qty;
    }
}