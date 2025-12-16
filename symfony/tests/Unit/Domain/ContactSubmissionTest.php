<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\ContactSubmission;
use App\Domain\Exception\PrivacyPolicyRequiredException;
use App\Domain\ValueObject\EmailAddress;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ContactSubmissionTest extends TestCase
{
    private const FULL_NAME = 'John Doe';
    private const EMAIL_STRING = 'john.doe@example.com';
    private const MESSAGE_CONTENT = 'This is a test message.';

    public function testCanBeCreatedBySubmissionMethod(): void
    {
        $submission = ContactSubmission::submit(
            self::FULL_NAME,
            self::EMAIL_STRING,
            self::MESSAGE_CONTENT,
            true
        );

        self::assertInstanceOf(ContactSubmission::class, $submission);
        
        self::assertSame(self::FULL_NAME, $submission->getFullName());
        self::assertSame(self::MESSAGE_CONTENT, $submission->getMessageContent());
        self::assertTrue($submission->isPrivacyPolicyAccepted());
        
        self::assertInstanceOf(EmailAddress::class, $submission->getEmail());
        self::assertSame(self::EMAIL_STRING, $submission->getEmail()->getValue());
        
        self::assertInstanceOf(DateTimeImmutable::class, $submission->getSubmittedAt());
    }

    public function testSubmittingWithoutPrivacyPolicyAcceptanceThrowsException(): void
    {
        self::expectException(PrivacyPolicyRequiredException::class);
        
        ContactSubmission::submit(
            self::FULL_NAME,
            self::EMAIL_STRING,
            self::MESSAGE_CONTENT,
            false
        );
    }
}