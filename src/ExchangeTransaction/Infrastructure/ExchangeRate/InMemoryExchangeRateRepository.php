<?php

namespace App\ExchangeTransaction\Infrastructure\ExchangeRate;

use App\ExchangeTransaction\Domain\ExchangeRate;
use App\ExchangeTransaction\Domain\IExchangeRateRepository;
use App\SharedKernel\Domain\Currency;

class InMemoryExchangeRateRepository implements IExchangeRateRepository
{
    private array $exchangeRates = [];

    public function __construct()
    {
        $this->exchangeRates[] = new ExchangeRate(new Currency('EUR'), new Currency('GBP'), 1.5678);
        $this->exchangeRates[] = new ExchangeRate(new Currency('GBP'), new Currency('EUR'), 1.5432);
    }

    public function getAll(): array
    {
        return $this->exchangeRates;
    }
}
