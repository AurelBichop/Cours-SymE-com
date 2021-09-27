<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger){}

    public static function getSubscribedEvents()
    {
        return [
            'product.view'=>'viewProduct'
        ];
    }

    public function viewProduct(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("le produit :{$productViewEvent->getProduct()->getId()} à été vue");
    }
}