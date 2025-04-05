<?php

namespace App\Infrastructure\Events;

use App\Domain\Products\Events\DomainEventDispatcher;
use Illuminate\Events\Dispatcher;

class LaravelDomainEventDispatcher implements DomainEventDispatcher
{
    public function __construct(
        private Dispatcher $dispatcher
    ) {}

    public function dispatch($event): void
    {
        $this->dispatcher->dispatch($event);
    }
}