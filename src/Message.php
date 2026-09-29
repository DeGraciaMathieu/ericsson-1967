<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// Le vocabulaire fermé des signaux : tout nom transporté par un
// Signal est l'un de ces cas, jamais une chaîne libre.
// ------------------------------------------------------------------

enum Message: string
{
    case DEMANDE_INSCRIPTION  = 'DEMANDE_INSCRIPTION';
    case INSCRIPTION_DEMANDEE = 'INSCRIPTION_DEMANDEE';
    case EXAMEN_VERIFIE       = 'EXAMEN_VERIFIE';
    case EXAMEN_INCONNU       = 'EXAMEN_INCONNU';
    case SESSION_CREEE        = 'SESSION_CREEE';
    case SESSION_ENREGISTREE  = 'SESSION_ENREGISTREE';
}
