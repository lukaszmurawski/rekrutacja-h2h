<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;
use App\Domain\ValueObject\EmailAddress;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EmailAddressTest extends TestCase
{
    public function testCanBeCreatedWithValidEmail(): void
    {
        $validEmail = 'test.user@example.com';
        $emailAddress = new EmailAddress($validEmail);

        self::assertSame($validEmail, $emailAddress->getValue());
        self::assertSame($validEmail, (string) $emailAddress);
    }

    #[DataProvider('provideInvalidEmails')]
    public function testThrowsExceptionForInvalidEmail(string $invalidEmail): void
    {
        self::expectException(InvalidEmailException::class);
        new EmailAddress($invalidEmail);
    }

    public function testEqualsMethod(): void
    {
        $email1 = new EmailAddress('same@example.com');
        $email2 = new EmailAddress('same@example.com');
        $email3 = new EmailAddress('different@example.com');

        self::assertTrue($email1->equals($email2), 'Should be equal.');
        self::assertFalse($email1->equals($email3), 'Should not be equal.');
    }

    /**
     * @return iterable<string, array<string>>
     */
    public static function provideInvalidEmails(): iterable
    {
        yield 'empty string' => [''];
        yield 'at is missing' => ['test.example.com'];
        yield 'incorrect format' => ['test@.com'];
        yield 'white space' => ['test @example.com'];
        yield 'special char' => ['te)st@example.com'];
        yield 'missing domain' => ['user@'];
        yield 'missing local part' => ['@example.com'];
    }
}