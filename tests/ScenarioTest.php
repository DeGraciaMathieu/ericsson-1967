<?php

declare(strict_types=1);

namespace Ericsson\Tests;

use Ericsson\Blocks\Candidates;
use Ericsson\Blocks\Catalog;
use Ericsson\Blocks\Notifications;
use Ericsson\Blocks\Sessions;
use Ericsson\Executive;
use Ericsson\Message;
use Ericsson\Signal;
use PHPUnit\Framework\TestCase;

// ------------------------------------------------------------------
// Macro tests: an external event enters the whole system, the signal
// chain unfolds, and we observe the only observable surface — the
// notifications printed by the system.
// ------------------------------------------------------------------

final class ScenarioTest extends TestCase
{
    public function test_a_valid_registration_is_confirmed(): void
    {
        $output = $this->play(
            new Signal(Message::REGISTRATION_REQUESTED, 'CANDIDATE', ['candidate' => 42, 'exam' => 7])
        );

        $this->assertStringContainsString('registration confirmed, session 1', $output);
    }

    public function test_an_unknown_exam_is_reported(): void
    {
        $output = $this->play(
            new Signal(Message::REGISTRATION_REQUESTED, 'CANDIDATE', ['candidate' => 42, 'exam' => 99])
        );

        $this->assertStringContainsString('exam 99 unknown', $output);
        $this->assertStringNotContainsString('registration confirmed', $output);
    }

    public function test_an_unknown_candidate_is_ignored(): void
    {
        $output = $this->play(
            new Signal(Message::REGISTRATION_REQUESTED, 'CANDIDATE', ['candidate' => 99, 'exam' => 7])
        );

        $this->assertStringNotContainsString('registration confirmed', $output);
        $this->assertStringNotContainsString('unknown', $output);
    }

    public function test_two_concurrent_registrations_each_get_their_own_session(): void
    {
        $output = $this->play(
            new Signal(Message::REGISTRATION_REQUESTED, 'CANDIDATE', ['candidate' => 42, 'exam' => 7]),
            new Signal(Message::REGISTRATION_REQUESTED, 'CANDIDATE', ['candidate' => 43, 'exam' => 8])
        );

        $this->assertStringContainsString('registration confirmed, session 1', $output);
        $this->assertStringContainsString('registration confirmed, session 2', $output);
    }

    private function play(Signal ...$entries): string
    {
        $executive = new Executive();
        $executive->load('CANDIDATE', new Candidates($executive));
        $executive->load('CATALOG', new Catalog($executive));
        $executive->load('SESSION', new Sessions($executive));
        $executive->load('NOTIFICATION', new Notifications($executive));

        foreach ($entries as $entry) {
            $executive->dispatch($entry);
        }

        ob_start();
        $executive->run();

        return ob_get_clean();
    }
}
