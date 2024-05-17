<?php

namespace App\ExchangeTransaction\Domain;

enum TransactionType
{
    case BUY;
    case SELL;
}
