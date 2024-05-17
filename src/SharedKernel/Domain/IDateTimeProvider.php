<?php

namespace App\SharedKernel\Domain;

interface IDateTimeProvider
{
    public function now(): \DateTimeImmutable;
}
