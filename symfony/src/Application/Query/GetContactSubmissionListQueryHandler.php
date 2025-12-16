<?php

namespace App\Application\Query;

use App\Domain\Repository\ContactSubmissionRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final readonly class GetContactSubmissionListQueryHandler
{
    public function __construct(
        private ContactSubmissionRepositoryInterface $contactSubmissionRepository
    ) {
    }

    public function __invoke(GetContactSubmissionListQuery $query): array
    {
        return $this->contactSubmissionRepository->findAllAsListItems($query->limit);
    }
}