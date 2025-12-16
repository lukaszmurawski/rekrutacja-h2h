<?php

declare(strict_types=1);

namespace App\Application\Query;
final readonly class GetContactSubmissionListQuery
{
    public function __construct(
        public ?int $limit = null,
    ) {
    }
}