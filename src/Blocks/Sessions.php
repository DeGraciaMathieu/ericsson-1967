<?php

declare(strict_types=1);

namespace Ericsson\Blocks;

use Ericsson\Block;
use Ericsson\Message;
use Ericsson\Signal;

final class Sessions extends Block
{
    /** @var array<int, string> */
    private array $states = [];
    private array $sessions = [];

    public function receive(Signal $signal): void
    {
        match ($signal->name) {
            Message::EXAM_VERIFIED      => $this->createSession($signal->data),
            Message::SESSION_REGISTERED => $this->register($signal->data['session']),
            default                     => null,
        };
    }

    private function createSession(array $data): void
    {
        $id = count($this->sessions) + 1;
        $this->sessions[$id] = $data;
        $this->states[$id] = 'CREATED';

        $this->send(Message::SESSION_CREATED, to: 'NOTIFICATION', data: ['session' => $id] + $data);
        $this->send(Message::SESSION_REGISTERED, to: 'SESSION', data: ['session' => $id]);
    }

    private function register(int $id): void
    {
        $this->states[$id] = 'REGISTERED';
    }
}
