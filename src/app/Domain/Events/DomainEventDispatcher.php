<?php

namespace App\Domain\Products\Events;

interface DomainEventDispatcher
{
    /**
     * Dispatch a domain event
     * @param object $event
     */
    public function dispatch($event): void;
}