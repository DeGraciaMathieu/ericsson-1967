<?php

declare(strict_types=1);

namespace Ericsson;

use SplQueue;

// ------------------------------------------------------------------
// L'exécutif : des files de signaux et une table d'adressage.
// Il distribue sans lire. Il ne sait pas qu'une inscription existe.
// ------------------------------------------------------------------

final class Executif
{
    /** @var array<string, Bloc> */
    private array $blocs = [];
    private SplQueue $file;

    public function __construct()
    {
        $this->file = new SplQueue();
    }

    public function charge(string $nom, Bloc $bloc): void
    {
        $this->blocs[$nom] = $bloc;
    }

    public function depose(Signal $signal): void
    {
        $this->file->enqueue($signal);
    }

    public function tourne(): void
    {
        while (! $this->file->isEmpty()) {
            $signal = $this->file->dequeue();
            echo sprintf("  → %-12s %s\n", $signal->vers, $signal->nom->value);
            sleep(1);
            $this->blocs[$signal->vers]->recoit($signal);
        }
    }
}
