<?php

namespace App\Inspection;

class Detector
{
    public function __construct(
        private float $seuil
    ){}

    public function detect(float $price):bool
    {
        return $price > $this->seuil;
    }
}