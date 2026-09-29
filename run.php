<?php

declare(strict_types=1);

use Ericsson\Executif;
use Ericsson\Message;
use Ericsson\Signal;
use Ericsson\Blocs\Candidats;
use Ericsson\Blocs\Catalogue;
use Ericsson\Blocs\Sessions;
use Ericsson\Blocs\Notifications;

require __DIR__ . '/vendor/autoload.php';

$executif = new Executif();
$executif->charge('CANDIDAT',     new Candidats($executif));
$executif->charge('CATALOGUE',    new Catalogue($executif));
$executif->charge('SESSION',      new Sessions($executif));
$executif->charge('NOTIFICATION', new Notifications($executif));

$executif->depose(new Signal(
    Message::DEMANDE_INSCRIPTION,
    'CANDIDAT', ['candidat' => 42, 'examen' => 7]
));

// $executif->depose(new Signal(
//     Message::DEMANDE_INSCRIPTION,
//     'CANDIDAT',
//     ['candidat' => 43, 'examen' => 8]
// ));

$executif->tourne();
