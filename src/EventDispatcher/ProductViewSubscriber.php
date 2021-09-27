<?php

namespace App\EventDispatcher;

use App\Entity\Product;
use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger, protected MailerInterface $mailer){}

    public static function getSubscribedEvents()
    {
        return [
            'product.view'=>'viewProduct'
        ];
    }

    public function viewProduct(ProductViewEvent $productViewEvent)
    {
//        $email = new TemplatedEmail();
//        $email->from(new Address('contact@mail.com', "Info de la boutique"))
//            ->to("admin@admin.com")
//            ->text("un visiteur regarde le produit {$productViewEvent->getProduct()->getName()}")
//            ->htmlTemplate('emails/product_view.html.twig')
//            ->context(["product"=>$productViewEvent->getProduct()])
//            ->subject("Visite du produit n°{$productViewEvent->getProduct()->getId()}");
//        $this->mailer->send($email);

        $this->logger->info("le produit :{$productViewEvent->getProduct()->getId()} à été vue");
    }
}