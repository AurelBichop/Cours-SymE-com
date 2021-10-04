<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('amount',[$this,'amount'])
        ];
    }

    public function amount($value,string $symbole =' €',string $decsep = ',',string $thousandsep = ' '){
        return number_format($value/100,2,$decsep,$thousandsep ).$symbole;
    }
}