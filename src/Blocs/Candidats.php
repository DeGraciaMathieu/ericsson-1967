<?php

declare(strict_types=1);

namespace Ericsson\Blocs;

use Ericsson\Bloc;
use Ericsson\Message;
use Ericsson\Signal;

final class Candidats extends Bloc
{
    private array $candidats = [42 => 'Ada', 43 => 'Grace'];

    public function recoit(Signal $signal): void
    {
        match ($signal->nom) {
            Message::DEMANDE_INSCRIPTION => $this->verifieLeCandidat($signal->donnees),
            default => null,
        };
    }

    private function verifieLeCandidat(array $donnees): void
    {
        if (!isset($this->candidats[$donnees['candidat']])) {
            return;
        }

        $this->envoie(Message::INSCRIPTION_DEMANDEE, vers: 'CATALOGUE', donnees: $donnees);
    }
}
