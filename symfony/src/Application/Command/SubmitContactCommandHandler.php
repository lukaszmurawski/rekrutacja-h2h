<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\ContactSubmission;
use App\Domain\Repository\ContactSubmissionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final readonly class SubmitContactCommandHandler
{
    public function __construct(
        private ContactSubmissionRepositoryInterface $contactRepository
    ) {
    }

    public function __invoke(SubmitContactCommand $command): void
    {
        $contact = ContactSubmission::submit(
            $command->fullName,
            $command->email,
            $command->messageContent,
            $command->privacyPolicyAccepted
        );

        $this->contactRepository->save($contact, true);
    }
}