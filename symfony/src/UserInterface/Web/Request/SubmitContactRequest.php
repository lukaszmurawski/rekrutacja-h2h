<?php

declare(strict_types=1);

namespace App\UserInterface\Web\Request; 

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'SubmitContactRequest',
    properties: [
        new OA\Property(property: 'fullName', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@domain.com'),
        new OA\Property(property: 'messageContent', type: 'string', example: 'This is the message content.'),
        new OA\Property(property: 'privacyPolicyAccepted', type: 'boolean', example: true)
    ],
    required: ['fullName', 'email', 'messageContent', 'privacyPolicyAccepted']
)]
final class SubmitContactRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Full name is required.")]
        #[Assert\Type(type: 'string', message: 'Full name must be a string.')]
        public readonly ?string $fullName,

        #[Assert\NotBlank(message: "Email address is required.")]
        #[Assert\Type(type: 'string', message: 'Email address must be a string.')]
        #[Assert\Email(message: "The email address is not valid.")]
        public readonly ?string $email,

        #[Assert\NotBlank(message: "Message content is required.")]
        #[Assert\Type(type: 'string', message: 'Message content must be a string.')]
        public readonly ?string $messageContent,

        #[Assert\NotNull(message: "Consent is required.")]
        #[Assert\IsTrue(message: "You must agree to the processing of personal data.")]
        public readonly ?bool $privacyPolicyAccepted
    ) {
    }
}