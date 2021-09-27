<?php

namespace App\Event;

use App\Entity\Product;
use Symfony\Contracts\EventDispatcher\Event;

class ProductViewEvent extends Event
{
    public function __construct(private Product $product){}

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

}