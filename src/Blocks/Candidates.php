<?php

declare(strict_types=1);

namespace Ericsson\Blocks;

use Ericsson\Block;
use Ericsson\Message;
use Ericsson\Signal;

final class Candidates extends Block
{
    private array $candidates = [42 => 'Ada', 43 => 'Grace'];

    public function receive(Signal $signal): void
    {
        match ($signal->name) {
            Message::REGISTRATION_REQUESTED => $this->checkCandidate($signal->data),
            default => null,
        };
    }

    private function checkCandidate(array $data): void
    {
        if (!isset($this->candidates[$data['candidate']])) {
            return;
        }

        $this->send(Message::REGISTRATION_SUBMITTED, to: 'CATALOG', data: $data);
    }
}
