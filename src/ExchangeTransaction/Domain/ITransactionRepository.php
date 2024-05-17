<?php

namespace App\ExchangeTransaction\Domain;

interface ITransactionRepository
{
    public function get(TransactionId $projectId): Transaction;

    public function persist(Transaction $transaction): void;
}
