<?php

declare(strict_types=1);

namespace Ericsson\Blocks;

use Ericsson\Block;
use Ericsson\Message;
use Ericsson\Signal;

final class Sessions extends Block
{
    private string $state = 'IDLE';
    private array $sessions = [];

    public function receive(Signal $signal): void
    {
        match ([$this->state, $signal->name]) {
            ['IDLE', Message::EXAM_VERIFIED]      => $this->createSession($signal->data),
            ['BUSY', Message::SESSION_REGISTERED] => $this->release(),
            default                               => null,
        };
    }

    private function createSession(array $data): void
    {
        $this->state = 'BUSY';
        $id = count($this->sessions) + 1;
        $this->sessions[$id] = $data;

        $this->send(Message::SESSION_CREATED, to: 'NOTIFICATION', data: ['session' => $id] + $data);
        $this->send(Message::SESSION_REGISTERED, to: 'SESSION');
    }

    private function release(): void
    {
        $this->state = 'IDLE';
    }
}
