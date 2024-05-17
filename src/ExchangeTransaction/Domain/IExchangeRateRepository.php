<?php

namespace App\ExchangeTransaction\Domain;

interface IExchangeRateRepository
{
    /** @return array<ExchangeRate> */
    public function getAll(): array;
}
