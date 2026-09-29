<?php

declare(strict_types=1);

namespace Ericsson\Blocks;

use Ericsson\Block;
use Ericsson\Message;
use Ericsson\Signal;

final class Notifications extends Block
{
    public function receive(Signal $signal): void
    {
        match ($signal->name) {
            Message::SESSION_CREATED => print("    ✉ registration confirmed, session {$signal->data['session']}\n"),
            Message::EXAM_UNKNOWN    => print("    ✉ exam {$signal->data['exam']} unknown\n"),
            default                  => null,
        };
    }
}
