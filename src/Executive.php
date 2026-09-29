<?php

declare(strict_types=1);

namespace Ericsson;

use SplQueue;

// ------------------------------------------------------------------
// The executive: signal queues and an address table.
// It routes without reading. It does not know a registration exists.
// ------------------------------------------------------------------

final class Executive
{
    /** @var array<string, Block> */
    private array $blocks = [];
    private SplQueue $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    public function load(string $name, Block $block): void
    {
        $this->blocks[$name] = $block;
    }

    public function dispatch(Signal $signal): void
    {
        $this->queue->enqueue($signal);
    }

    public function run(): void
    {
        while (! $this->queue->isEmpty()) {
            $signal = $this->queue->dequeue();
            echo sprintf("  → %-12s %s\n", $signal->to, $signal->name->value);
            sleep(1);
            $this->blocks[$signal->to]->receive($signal);
        }
    }
}
