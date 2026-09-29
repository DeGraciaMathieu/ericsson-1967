<?php

declare(strict_types=1);

namespace Ericsson;

// ------------------------------------------------------------------
// The closed vocabulary of signals: every name carried by a Signal
// is one of these cases, never a free-form string.
// ------------------------------------------------------------------

enum Message: string
{
    case REGISTRATION_REQUESTED = 'REGISTRATION_REQUESTED';
    case REGISTRATION_SUBMITTED = 'REGISTRATION_SUBMITTED';
    case EXAM_VERIFIED          = 'EXAM_VERIFIED';
    case EXAM_UNKNOWN           = 'EXAM_UNKNOWN';
    case SESSION_CREATED        = 'SESSION_CREATED';
    case SESSION_REGISTERED     = 'SESSION_REGISTERED';
}
