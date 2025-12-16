<?php

declare(strict_types=1);

namespace App\Infrastructure\Messenger;

use App\Application\Query\QueryBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyQueryBus implements QueryBusInterface
{
    public function __construct(
        private MessageBusInterface $queryBus
    ) {
    }

    public function query(object $query): mixed
    {
        $envelope = $this->queryBus->dispatch($query);

        /** @var HandledStamp|null $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        if (!$handledStamp) {
            throw new \LogicException(sprintf(
                'Query "%s" was not handled synchronously or Query Bus is misconfigured. Ensure a synchronous handler is defined.',
                get_class($query)
            ));
        }

        return $handledStamp->getResult();
    }
}