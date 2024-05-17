<?php

namespace App\SharedKernel\Domain;

final readonly class Money
{
    public function __construct(
        private float $amount,
        private Currency $currency
    ) {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function applyFee(float $percentage): Money
    {
        $fee = $this->amount * ($percentage / 100);
        return new Money($this->amount - $fee, $this->currency);
    }
}
