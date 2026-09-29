<?php

declare(strict_types=1);

namespace Ericsson\Blocs;

use Ericsson\Bloc;
use Ericsson\Message;
use Ericsson\Signal;

final class Sessions extends Bloc
{
    private string $etat = 'REPOS';
    private array $sessions = [];

    public function recoit(Signal $signal): void
    {
        match ([$this->etat, $signal->nom]) {
            ['REPOS', Message::EXAMEN_VERIFIE]       => $this->creeLaSession($signal->donnees),
            ['OCCUPE', Message::SESSION_ENREGISTREE] => $this->libere(),
            default                           => null,
        };
    }

    private function creeLaSession(array $donnees): void
    {
        $this->etat = 'OCCUPE';
        $id = count($this->sessions) + 1;
        $this->sessions[$id] = $donnees;

        $this->envoie(Message::SESSION_CREEE, vers: 'NOTIFICATION', donnees: ['session' => $id] + $donnees);
        $this->envoie(Message::SESSION_ENREGISTREE, vers: 'SESSION');
    }

    private function libere(): void
    {
        $this->etat = 'REPOS';
    }
}
