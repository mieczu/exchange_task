<?php

namespace App\ExchangeTransaction\Domain;

class TransactionId
{
    public function __construct(public string $id)
    {
    }
}
