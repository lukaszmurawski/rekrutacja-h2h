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

        $this->assertSame($validEmail, $emailAddress->getValue());
        $this->assertSame($validEmail, (string) $emailAddress);
    }

    #[DataProvider('provideInvalidEmails')]
    public function testThrowsExceptionForInvalidEmail(string $invalidEmail): void
    {
        $this->expectException(InvalidEmailException::class);
        new EmailAddress($invalidEmail);
    }

    public function testEqualsMethod(): void
    {
        $email1 = new EmailAddress('same@example.com');
        $email2 = new EmailAddress('same@example.com');
        $email3 = new EmailAddress('different@example.com');

        $this->assertTrue($email1->equals($email2), 'Should be equal.');
        $this->assertFalse($email1->equals($email3), 'Should not be equal.');
    }

    /**
     * @return array<string, array<string>>
     */
    public static function provideInvalidEmails(): array
    {
        return [
            'empty string' => [''],
            'at is missing' => ['test.example.com'],
            'incorrect format' => ['test@.com'],
            'white space' => ['test @example.com'],
            'special char' => ['te$st@example.com'],
            'missing domain' => ['user@'],
            'missing local part' => ['@example.com'],
        ];
    }
}