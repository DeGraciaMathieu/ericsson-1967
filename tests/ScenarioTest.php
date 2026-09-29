<?php

declare(strict_types=1);

namespace Ericsson\Tests;

use Ericsson\Blocs\Candidats;
use Ericsson\Blocs\Catalogue;
use Ericsson\Blocs\Notifications;
use Ericsson\Blocs\Sessions;
use Ericsson\Executif;
use Ericsson\Message;
use Ericsson\Signal;
use PHPUnit\Framework\TestCase;

// ------------------------------------------------------------------
// Tests macro : un événement extérieur entre dans le système complet,
// on laisse la chaîne des signaux se dérouler, et on observe la seule
// surface observable — les notifications imprimées.
// ------------------------------------------------------------------

final class ScenarioTest extends TestCase
{
    public function test_une_inscription_valide_est_confirmee(): void
    {
        $sortie = $this->joue(
            new Signal(Message::DEMANDE_INSCRIPTION, 'CANDIDAT', ['candidat' => 42, 'examen' => 7])
        );

        $this->assertStringContainsString('inscription confirmée, session 1', $sortie);
    }

    public function test_un_examen_inconnu_est_signale(): void
    {
        $sortie = $this->joue(
            new Signal(Message::DEMANDE_INSCRIPTION, 'CANDIDAT', ['candidat' => 42, 'examen' => 99])
        );

        $this->assertStringContainsString('examen 99 inconnu', $sortie);
        $this->assertStringNotContainsString('inscription confirmée', $sortie);
    }

    public function test_un_candidat_inconnu_est_ignore(): void
    {
        $sortie = $this->joue(
            new Signal(Message::DEMANDE_INSCRIPTION, 'CANDIDAT', ['candidat' => 99, 'examen' => 7])
        );

        $this->assertStringNotContainsString('inscription confirmée', $sortie);
        $this->assertStringNotContainsString('inconnu', $sortie);
    }

    private function joue(Signal $entree): string
    {
        $executif = new Executif();
        $executif->charge('CANDIDAT', new Candidats($executif));
        $executif->charge('CATALOGUE', new Catalogue($executif));
        $executif->charge('SESSION', new Sessions($executif));
        $executif->charge('NOTIFICATION', new Notifications($executif));

        $executif->depose($entree);

        ob_start();
        $executif->tourne();

        return ob_get_clean();
    }
}
