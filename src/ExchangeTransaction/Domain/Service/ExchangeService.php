<?php

namespace App\ExchangeTransaction\Domain\Service;

use App\ExchangeTransaction\Domain\ExchangeRate;
use App\ExchangeTransaction\Domain\Transaction;
use App\ExchangeTransaction\Domain\TransactionType;
use App\SharedKernel\Domain\Currency;
use App\SharedKernel\Domain\Money;

class ExchangeService
{
    public const FEE = 1;
    private array $exchangeRates;

    public function __construct(array $exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
    }

    public function sell(Money $moneyToSell, Currency $toCurrency): Money
    {
        $exchangeRate = $this->findExchangeRate($moneyToSell->getCurrency(), $toCurrency);

        $moneyToSell = $moneyToSell->applyFee(self::FEE);
        $convertedMoney = $exchangeRate->convert($moneyToSell);

        return $convertedMoney;
    }

    public function buy(Money $moneyToBuy, Currency $fromCurrency): Money
    {
        $exchangeRate = $this->findExchangeRate($fromCurrency, $moneyToBuy->getCurrency());
        $convertedMoney = $exchangeRate->convert($moneyToBuy);

        return $convertedMoney->applyFee(self::FEE);
    }

    private function findExchangeRate(Currency $fromCurrency, Currency $toCurrency): ExchangeRate
    {
        foreach ($this->exchangeRates as $rate) {
            if ($rate->fromCurrency->getCode() === $fromCurrency->getCode() &&
                $rate->toCurrency->getCode() === $toCurrency->getCode()) {
                return $rate;
            }
        }

        throw new \Exception("Exchange rate not found.");
    }
}
