<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// A signal: a name, a named recipient, some values.
// Never a domain object inside — only identifiers and scalars.
// ------------------------------------------------------------------

final readonly class Signal
{
    public function __construct(
        public Message $name,
        public string $to,
        public array $data = [],
    ) {}
}
