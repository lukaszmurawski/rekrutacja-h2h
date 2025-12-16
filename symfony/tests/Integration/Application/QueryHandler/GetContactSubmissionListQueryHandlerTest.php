<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\QueryHandler;

use App\Application\Query\GetContactSubmissionListQuery;
use App\Application\Query\ContactSubmissionListItem;
use App\Tests\Fixtures\ContactSubmissionFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

final class GetContactSubmissionListQueryHandlerTest extends KernelTestCase
{
    private MessageBusInterface $queryBus;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->queryBus = $container->get('query.bus');
        
        $this->em = $container->get(EntityManagerInterface::class);

        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute([new ContactSubmissionFixture()]);
    }
    
    public function testHandlerReturnsAllSubmissionsAsSortedDtoList(): void
    {
        $query = new GetContactSubmissionListQuery();

        $envelope = $this->queryBus->dispatch($query);
        
        $listItems = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class)
            ->getResult();

        self::assertCount(3, $listItems, 'List should contain 3 submissions.');
        
        self::assertContainsOnlyInstancesOf(ContactSubmissionListItem::class, $listItems);
        
        $firstItem = $listItems[0];
        self::assertSame('Celina Nowa', $firstItem->fullName);
        self::assertSame('celina@o2.pl', $firstItem->email);

        /** @var ContactSubmissionListItem $lastItem */
        $lastItem = $listItems[2];
        self::assertSame('Alicja Stara', $lastItem->fullName);
        self::assertSame('alicia.stara@wp.pl', $lastItem->email);
    }

    public function testHandlerReturnsLimitedSubmissionsAsSortedDtoList(): void
    {
        $limit = 2;
        $query = new GetContactSubmissionListQuery($limit);

        $envelope = $this->queryBus->dispatch($query);
        
        $listItems = $envelope->last(\Symfony\Component\Messenger\Stamp\HandledStamp::class)
            ->getResult();

        self::assertCount($limit, $listItems, 'List should contain 2 submissions.');
    }
}