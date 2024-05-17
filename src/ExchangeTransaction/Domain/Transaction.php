<?php

namespace App\ExchangeTransaction\Domain;

use App\ExchangeTransaction\Domain\Events\TransactionFinished;
use App\ExchangeTransaction\Domain\Events\TransactionStarted;
use App\ExchangeTransaction\Domain\Exception\InvalidAmountException;
use App\ExchangeTransaction\Domain\Service\ExchangeService;
use App\SharedKernel\Domain\AggregateRoot;
use App\SharedKernel\Domain\Currency;
use App\SharedKernel\Domain\IDateTimeProvider;
use App\SharedKernel\Domain\Money;

class Transaction extends AggregateRoot
{
    private Money $moneyFrom;
    private Money $moneyTo;
    private ?TransactionType $type;

    public function __construct(
        private TransactionId $transactionId,
        private IDateTimeProvider $dateTimeProvider
    ) {
        $this->type = null;
        $this->publishEvent(
            new TransactionStarted(
                transactionId: $this->transactionId->id,
                dateStarted: $this->dateTimeProvider->now()->format(\DateTimeInterface::ATOM)
            )
        );
    }

    public function buy(
        Money             $moneyToBuy,
        Currency          $fromCurrency,
        ExchangeService   $exchangeService,
        IDateTimeProvider $dateTimeProvider
    ): void {
        if ($moneyToBuy->getAmount() <= 0) {
            throw new InvalidAmountException('Amount to buy must be greater than 0');
        }

        $this->type = TransactionType::BUY;
        $this->moneyTo = $moneyToBuy;

        $this->moneyFrom = $exchangeService->buy($moneyToBuy, $fromCurrency);

        $this->publishEvent(
            event: new TransactionFinished(
                transactionId: $this->transactionId->id,
                transactionType: $this->type->name,
                fromCurrency: $this->moneyFrom->getCurrency()->getCode(),
                fromAmount: (string)$this->moneyFrom->getAmount(),
                toCurrency: $moneyToBuy->getCurrency()->getCode(),
                toAmount: (string)$moneyToBuy->getAmount(),
                dateFinished: $dateTimeProvider->now()->format(\DateTimeInterface::ATOM)
            )
        );
    }

    public function sell(
        Money             $moneyToSell,
        Currency          $toCurrency,
        ExchangeService   $exchangeService,
        IDateTimeProvider $dateTimeProvider
    ): void {
        if ($moneyToSell->getAmount() <= 0) {
            throw new InvalidAmountException('Amount to sell must be greater than 0');
        }

        $this->type = TransactionType::SELL;
        $this->moneyFrom = $moneyToSell;
        $this->moneyTo = $exchangeService->sell($moneyToSell, $toCurrency);

        $this->publishEvent(
            event: new TransactionFinished(
                transactionId: $this->transactionId->id,
                transactionType: $this->type->name,
                fromCurrency: $moneyToSell->getCurrency()->getCode(),
                fromAmount: (string)$moneyToSell->getAmount(),
                toCurrency: $this->moneyTo->getCurrency()->getCode(),
                toAmount: (string)$this->moneyTo->getAmount(),
                dateFinished: $dateTimeProvider->now()->format(\DateTimeInterface::ATOM)
            )
        );
    }
}
