<?php

declare(strict_types=1);

namespace App\Tests\Unit\UserInterface\Web\Request;

use App\UserInterface\Web\Request\SubmitContactRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SubmitContactRequestTest extends KernelTestCase
{
    private const VALID_DATA = [
        'fullName' => 'Jane Doe',
        'email' => 'jane.doe@example.com',
        'messageContent' => 'Hello, I have a question.',
        'privacyPolicyAccepted' => true,
    ];

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get('validator');
    }

    public function testValidCommandPassesValidation(): void
    {
        $command = new SubmitContactRequest(
            self::VALID_DATA['fullName'],
            self::VALID_DATA['email'],
            self::VALID_DATA['messageContent'],
            self::VALID_DATA['privacyPolicyAccepted']
        );

        $violations = $this->validator->validate($command);

        $this->assertCount(0, $violations, 'Valid command should produce no violations.');
    }

    #[DataProvider('provideInvalidData')]
    public function testInvalidCommandFailsValidation(array $invalidData, string $expectedProperty, string $expectedMessage): void
    {
        $data = array_merge(self::VALID_DATA, $invalidData);

        $command = new SubmitContactRequest(
            $data['fullName'],
            $data['email'],
            $data['messageContent'],
            $data['privacyPolicyAccepted']
        );

        $violations = $this->validator->validate($command);

        $this->assertGreaterThan(0, $violations, 'Invalid command must produce at least one violation.');
        
        $found = false;

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            if ($violation->getPropertyPath() === $expectedProperty && $violation->getMessage() === $expectedMessage) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, sprintf(
            'Expected violation not found for property "%s" with message: "%s".', 
            $expectedProperty, 
            $expectedMessage
        ));
    }

    public static function provideInvalidData(): iterable
    {
        yield 'Missing full name (NotBlank)' => [
            ['fullName' => ''], 
            'fullName', 
            'Full name is required.'
        ];

        yield 'Invalid email format' => [
            ['email' => 'invalid-email-format'], 
            'email', 
            'The email address is not valid.'
        ];
        
        yield 'Missing email (NotBlank)' => [
            ['email' => ''], 
            'email', 
            'Email address is required.'
        ];

        yield 'Missing message content (NotBlank)' => [
            ['messageContent' => ''], 
            'messageContent', 
            'Message content is required.'
        ];

        yield 'Privacy policy not accepted (IsTrue)' => [
            ['privacyPolicyAccepted' => false], 
            'privacyPolicyAccepted', 
            'You must agree to the processing of personal data.'
        ];
        
         yield 'Privacy policy is null (NotNull)' => [
            ['privacyPolicyAccepted' => null], 
            'privacyPolicyAccepted', 
            'Consent is required.'
        ];
    }
}