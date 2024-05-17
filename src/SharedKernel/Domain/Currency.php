<?php

namespace App\SharedKernel\Domain;

final readonly class Currency
{
    public function __construct(
        private string $code
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
