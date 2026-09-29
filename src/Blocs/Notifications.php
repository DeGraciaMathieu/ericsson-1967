<?php

declare(strict_types=1);

namespace Ericsson\Blocs;

use Ericsson\Bloc;
use Ericsson\Message;
use Ericsson\Signal;

final class Notifications extends Bloc
{
    public function recoit(Signal $signal): void
    {
        match ($signal->nom) {
            Message::SESSION_CREEE  => print("    ✉ inscription confirmée, session {$signal->donnees['session']}\n"),
            Message::EXAMEN_INCONNU => print("    ✉ examen {$signal->donnees['examen']} inconnu\n"),
            default          => null,
        };
    }
}
