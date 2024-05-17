<?php

namespace App\ExchangeTransaction\Domain;

use App\SharedKernel\Domain\Currency;
use App\SharedKernel\Domain\Money;

class ExchangeRate
{
    public function __construct(public Currency $fromCurrency, public Currency $toCurrency, public float $rate)
    {
    }

    public function convert(Money $money): Money
    {
        $amount = $money->getAmount() * $this->rate;
        return new Money($amount, $this->toCurrency);
    }
}
