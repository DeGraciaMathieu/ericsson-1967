<?php

declare(strict_types=1);

use Ericsson\Executive;
use Ericsson\Message;
use Ericsson\Signal;
use Ericsson\Blocks\Candidates;
use Ericsson\Blocks\Catalog;
use Ericsson\Blocks\Sessions;
use Ericsson\Blocks\Notifications;

require __DIR__ . '/vendor/autoload.php';

$executive = new Executive();
$executive->load('CANDIDATE',    new Candidates($executive));
$executive->load('CATALOG',      new Catalog($executive));
$executive->load('SESSION',      new Sessions($executive));
$executive->load('NOTIFICATION', new Notifications($executive));

$executive->dispatch(new Signal(
    Message::REGISTRATION_REQUESTED,
    'CANDIDATE', ['candidate' => 42, 'exam' => 7]
));

$executive->dispatch(new Signal(
    Message::REGISTRATION_REQUESTED,
    'CANDIDATE',
    ['candidate' => 43, 'exam' => 8]
));

$executive->run();
