<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// Un bloc : ses propres données, son propre état, et une seule porte
// d'entrée. Il ne connaît que l'exécutif et le nom de son successeur.
// ------------------------------------------------------------------

abstract class Bloc
{
    public function __construct(private Executif $executif) {}

    abstract public function recoit(Signal $signal): void;

    protected function envoie(Message $nom, string $vers, array $donnees = []): void
    {
        $this->executif->depose(new Signal($nom, $vers, $donnees));
    }
}
