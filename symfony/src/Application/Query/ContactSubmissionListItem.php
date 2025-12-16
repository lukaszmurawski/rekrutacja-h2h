<?php

declare(strict_types=1);

namespace App\Application\Query;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ContactSubmissionListItem'
)]
final readonly class ContactSubmissionListItem
{
    #[OA\Property(type: 'string', format: 'date-time', example: '2025-12-16T10:00:00+01:00')]
    public readonly string $submittedAt;
    
    public function __construct(
        #[OA\Property(type: 'integer', example: 123)]
        public int $id,

        #[OA\Property(type: 'string', example: 'Jane Doe')]
        public string $fullName,

        #[OA\Property(type: 'string', format: 'email', example: 'jane.doe@example.com')]
        public string $email,

        #[OA\Property(type: 'string', example: 'The message body.')]
        public string $messageContent,

        \DateTimeInterface $submittedAt,
    ) {
        $this->submittedAt = $submittedAt->format('Y-m-d H:i:s');
    }
}