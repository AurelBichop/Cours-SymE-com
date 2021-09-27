<?php

namespace App\Stripe;

use App\Entity\Purchase;

class StripeService
{

    public function __construct(protected string $secretKey,protected string $publicKey){}

    public function getPayementIntent(Purchase $purchase){
        \Stripe\Stripe::setApiKey($this->secretKey);

        return \Stripe\PaymentIntent::create(
            [
                'amount' =>$purchase->getTotal(),
                'currency'=>'eur'
            ]
        );
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

}