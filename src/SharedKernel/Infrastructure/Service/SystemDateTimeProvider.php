<?php

namespace App\SharedKernel\Infrastructure\Service;

use App\SharedKernel\Domain\IDateTimeProvider;

class SystemDateTimeProvider implements IDateTimeProvider
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
