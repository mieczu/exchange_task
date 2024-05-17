<?php

namespace App\ExchangeTransaction\Domain\Events;

use App\SharedKernel\Domain\IDomainEvent;

final readonly class TransactionStarted implements IDomainEvent
{
    public function __construct(public string $transactionId, public string $dateStarted)
    {
    }
}
