<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ContactSubmission;
use App\Domain\Repository\ContactSubmissionRepositoryInterface;
use App\Application\Query\ContactSubmissionListItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ContactSubmissionRepository extends ServiceEntityRepository implements ContactSubmissionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactSubmission::class);
    }

    /**
     * @inheritDoc
     */
    public function save(ContactSubmission $submission, bool $flush = false): void
    {
        $this->getEntityManager()->persist($submission);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllAsListItems(?int $limit = null): array
    {
        $qb = $this->createQueryBuilder('cs')
            ->select('NEW ' . ContactSubmissionListItem::class . '(cs.id, cs.fullName, cs.email.email, cs.messageContent, cs.submittedAt)')
            ->orderBy('cs.submittedAt', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function find(
        $id, 
        $lockMode = null, 
        $lockVersion = null
    ): ?ContactSubmission
    {
        return parent::find($id, $lockMode, $lockVersion); 
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function count(array $criteria = []): int
    {
        return parent::count($criteria);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?ContactSubmission
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}