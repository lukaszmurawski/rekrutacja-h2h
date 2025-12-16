<?php

declare(strict_types=1);

namespace App\UserInterface\Web\Response;

use OpenApi\Attributes as OA;
use App\Application\Query\ContactSubmissionListItem;

#[OA\Schema(
    schema: 'ContactSubmissionsResponse'
)]
final readonly class ContactSubmissionsResponse
{
    /**
     * @param ContactSubmissionListItem[] $items
     * Reprezentuje listę zgłoszeń kontaktowych.
     */
    #[OA\Property(
        description: 'List of contact submissions',
        type: 'array',
        items: new OA\Items(ref: '#/components/schemas/ContactSubmissionListItem')
    )]
    public array $items;

    /**
     * @param ContactSubmissionListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}