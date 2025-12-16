<?php

declare(strict_types=1);

namespace App\Application\Query;

interface QueryBusInterface
{
    /**
     * @throws \LogicException
     */
    public function query(object $query): mixed;
}