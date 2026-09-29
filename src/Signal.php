<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// Un signal : un nom, un destinataire nommé, des valeurs.
// Jamais d'objet métier dedans — seulement des identifiants et des scalaires.
// ------------------------------------------------------------------

final readonly class Signal
{
    public function __construct(
        public Message $nom,
        public string $vers,
        public array $donnees = [],
    ) {}
}
