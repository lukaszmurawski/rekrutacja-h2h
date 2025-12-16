<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Messenger;

use App\Infrastructure\Messenger\SymfonyQueryBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class DummyQuery
{
}

class DummyResultDto
{
}

final class SymfonyQueryBusTest extends TestCase
{
    private MessageBusInterface $messageBusMock;
    private SymfonyQueryBus $symfonyQueryBus;

    protected function setUp(): void
    {
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
        $this->symfonyQueryBus = new SymfonyQueryBus($this->messageBusMock);
    }

    public function testQuery_WhenHandledSynchronously_ReturnsResult(): void
    {
        $query = new DummyQuery();
        $expectedResult = new DummyResultDto();
        
        $handledStamp = new HandledStamp($expectedResult, 'SomeHandlerClass');
        
        $envelope = new Envelope($query, [$handledStamp]);
        
        $this->messageBusMock
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(DummyQuery::class))
            ->willReturn($envelope);

        $actualResult = $this->symfonyQueryBus->query($query);

        self::assertSame($expectedResult, $actualResult);
    }

    public function testQuery_WhenNotHandledSynchronously_ThrowsLogicException(): void
    {
        $query = new DummyQuery();
        
        $envelope = new Envelope($query, [/* Brak HandledStamp */]);
        
       $this->messageBusMock
            ->expects(self::once())
            ->method('dispatch')
            ->willReturn($envelope);

        self::expectException(\LogicException::class);
        
        self::expectExceptionMessageMatches('/Query ".*DummyQuery" was not handled synchronously/');

        $this->symfonyQueryBus->query($query);
    }
}