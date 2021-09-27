<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger,protected MailerInterface $mailer, protected Security $security){}


    public static function getSubscribedEvents()
    {
        return [
          'purchase.success'=>'sendSuccessEmail'
        ];
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $purchase = $purchaseSuccessEvent->getPurchase();

        $email = new TemplatedEmail();

        $email->to(new Address($currentUser->getEmail(),$currentUser->getFullName()))
            ->from('contact@mai.com')
            ->subject("Bravo votre commande {$purchase->getId()} vient d'être commandé")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context([
                'purchase'=>$purchase,
                'user'=>$currentUser
            ])
        ;

        $this->mailer->send($email);

        $this->logger->info("email envoyé pour la commande n° ".$purchaseSuccessEvent->getPurchase()->getId());
    }

}