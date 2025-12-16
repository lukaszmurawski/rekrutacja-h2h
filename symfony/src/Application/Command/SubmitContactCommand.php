<?php

declare(strict_types=1);

namespace App\Application\Command;

final readonly class SubmitContactCommand
{
    public function __construct(
        public string $fullName,
        public string $email,
        public string $messageContent,
        public bool $privacyPolicyAccepted
    ) {
    }
}