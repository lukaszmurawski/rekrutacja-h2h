<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\ContactSubmission;
use App\Application\Query\ContactSubmissionListItem;

interface ContactSubmissionRepositoryInterface
{
    /**
     * @param ContactSubmission $submission
     * @param boolean $flush
     * 
     * @return void
     */
    public function save(ContactSubmission $submission, bool $flush = false): void;
    
    /**
     * @return ContactSubmissionListItem[]
     */
    public function findAllAsListItems(?int $limit = null): array;

    /**
     * @param $id
     * @param $lockMode
     * @param $lockVersion
     * 
     * @return ContactSubmission|null
     */
    public function find(
        $id,
        $lockMode = null,
        $lockVersion = null
    ): ?ContactSubmission;

    /**
     * @param array $criteria
     * 
     * @return integer
     */
    public function count(array $criteria = []): int;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * 
     * @return ContactSubmission|null
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?ContactSubmission;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param [type] $limit
     * @param [type] $offset
     * 
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array;

    /**
     * @return array
     */
    public function findAll(): array;
}