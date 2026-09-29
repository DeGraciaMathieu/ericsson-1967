<?php

declare(strict_types=1);

namespace Ericsson\Blocs;

use Ericsson\Bloc;
use Ericsson\Message;
use Ericsson\Signal;

final class Catalogue extends Bloc
{
    private array $examens = [7 => 'TOEIC', 8 => 'IELTS'];

    public function recoit(Signal $signal): void
    {
        match ($signal->nom) {
            Message::INSCRIPTION_DEMANDEE => $this->verifieLExamen($signal->donnees),
            default => null,
        };
    }

    private function verifieLExamen(array $donnees): void
    {
        if (!isset($this->examens[$donnees['examen']])) {
            $this->envoie(Message::EXAMEN_INCONNU, vers: 'NOTIFICATION', donnees: $donnees);
            return;
        }

        $this->envoie(Message::EXAMEN_VERIFIE, vers: 'SESSION', donnees: $donnees);
    }
}
