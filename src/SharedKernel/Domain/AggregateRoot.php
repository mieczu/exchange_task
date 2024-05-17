<?php

namespace App\SharedKernel\Domain;

abstract class AggregateRoot
{
    private array $eventsToPublish;

    protected function publishEvent(IDomainEvent $event): void
    {
        $this->eventsToPublish[] = $event;
    }

    public function getEventsToPublish(): array
    {
        return $this->eventsToPublish;
    }

}
