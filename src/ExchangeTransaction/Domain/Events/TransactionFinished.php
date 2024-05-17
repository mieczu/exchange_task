<?php

namespace App\ExchangeTransaction\Domain\Events;

use App\SharedKernel\Domain\IDomainEvent;

final readonly class TransactionFinished implements IDomainEvent
{
    public function __construct(
        public string $transactionId,
        public string $transactionType,
        public string $fromCurrency,
        public string $fromAmount,
        public string $toCurrency,
        public string $toAmount,
        public string $dateFinished
    ) {
    }
}
