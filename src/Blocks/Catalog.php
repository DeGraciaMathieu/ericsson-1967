<?php

declare(strict_types=1);

namespace Ericsson\Blocks;

use Ericsson\Block;
use Ericsson\Message;
use Ericsson\Signal;

final class Catalog extends Block
{
    private array $exams = [7 => 'TOEIC', 8 => 'IELTS'];

    public function receive(Signal $signal): void
    {
        match ($signal->name) {
            Message::REGISTRATION_SUBMITTED => $this->checkExam($signal->data),
            default => null,
        };
    }

    private function checkExam(array $data): void
    {
        if (!isset($this->exams[$data['exam']])) {
            $this->send(Message::EXAM_UNKNOWN, to: 'NOTIFICATION', data: $data);
            return;
        }

        $this->send(Message::EXAM_VERIFIED, to: 'SESSION', data: $data);
    }
}
