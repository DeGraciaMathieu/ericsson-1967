<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// A block: its own data, its own state, and a single entry door.
// It only knows the executive and the name of its successor.
// ------------------------------------------------------------------

abstract class Block
{
    public function __construct(private Executive $executive) {}

    abstract public function receive(Signal $signal): void;

    protected function send(Message $name, string $to, array $data = []): void
    {
        $this->executive->dispatch(new Signal($name, $to, $data));
    }
}
