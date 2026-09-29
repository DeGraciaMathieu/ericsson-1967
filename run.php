<?php

declare(strict_types=1);

use Ericsson\Executive;
use Ericsson\Message;
use Ericsson\Signal;
use Ericsson\Blocks\Candidates;
use Ericsson\Blocks\Catalog;
use Ericsson\Blocks\Sessions;
use Ericsson\Blocks\Notifications;

// ------------------------------------------------------------------
// This file is a simulation harness — a real exchange had no such
// entry point. Two things differ from the original system:
//
//  1. The executive never "started" and never stopped. It was a
//     permanent real-time kernel: when the signal queue emptied it
//     did not return, it waited for the next signal. Our run() ending
//     on an empty queue is purely a simulation artifact.
//
//  2. The initial dispatch() below replaces a physical external event.
//     Input signals (those not sent by another block) were produced by
//     the hardware: a subscriber going off-hook, line/junction scanning,
//     interrupts. "Candidate 42 requests a registration" is the analogue
//     of a subscriber lifting the handset — the event is born in the
//     physical world and the scanning hardware turns it into a signal.
//
// Once a signal is in the queue, everything below is faithful: the
// executive dequeues it and calls the recipient block's single entry
// door (Executive::run -> Block::receive).
// ------------------------------------------------------------------

require __DIR__ . '/vendor/autoload.php';

$executive = new Executive();
$executive->load('CANDIDATE',    new Candidates($executive));
$executive->load('CATALOG',      new Catalog($executive));
$executive->load('SESSION',      new Sessions($executive));
$executive->load('NOTIFICATION', new Notifications($executive));

// External events entering the system — the scanning hardware would emit these.
$executive->dispatch(new Signal(
    Message::REGISTRATION_REQUESTED,
    'CANDIDATE',
    ['candidate' => 42, 'exam' => 7],
));

$executive->dispatch(new Signal(
    Message::REGISTRATION_REQUESTED,
    'CANDIDATE',
    ['candidate' => 43, 'exam' => 8],
));

// In a real exchange this never returns; here it drains the queue and stops.
$executive->run();
