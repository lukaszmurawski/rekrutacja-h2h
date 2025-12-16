<?php

declare(strict_types=1);

namespace App\UserInterface\Web;

use OpenApi\Attributes as OA;
use App\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\Command\SubmitContactCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Application\Query\ContactSubmissionListItem;
use Symfony\Component\Messenger\MessageBusInterface;
use App\UserInterface\Web\Request\SubmitContactRequest;
use App\Application\Query\GetContactSubmissionListQuery;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\UserInterface\Web\Response\ContactSubmissionsResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/contact')]
final class ContactSubmissionController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus, 
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('', methods: ['POST'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/SubmitContactRequest')
    )]
    #[OA\Response(
        response: 202,
        description: 'Contact submission accepted for processing.'
    )]
    #[OA\Response(
        response: 400,
        description: 'Validation failed or bad request format.'
    )]
    public function submit(Request $request): JsonResponse
    {
        try {
            $submitContactRequest = $this->serializer->deserialize(
                $request->getContent(),
                SubmitContactRequest::class,
                'json'
            );
        } catch (\Throwable $e) {
            return new JsonResponse(
                ['error' => 'Malformed JSON or invalid request format.'],
                Response::HTTP_BAD_REQUEST
            );
        } 
        
        $errors = $this->validator->validate($submitContactRequest);

        if (count($errors) > 0) {
            return $this->createValidationErrorResponse($errors);
        }

        $command = new SubmitContactCommand(
            fullName: $submitContactRequest->fullName,
            email: $submitContactRequest->email,
            messageContent: $submitContactRequest->messageContent,
            privacyPolicyAccepted: $submitContactRequest->privacyPolicyAccepted
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(
            ['message' => 'Submission received and accepted for processing.'],
            Response::HTTP_ACCEPTED
        );
    }

    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a list of contact submissions.',
        content: new OA\JsonContent(ref: '#/components/schemas/ContactSubmissionsResponse')
    )]
    public function getList(): JsonResponse
    {
        /** @var list<ContactSubmissionListItem> */
        $listItems = $this->queryBus->query(new GetContactSubmissionListQuery()); 

        return new JsonResponse(
            new ContactSubmissionsResponse($listItems),
            Response::HTTP_OK
        );
    }

    private function createValidationErrorResponse(ConstraintViolationListInterface $violations): JsonResponse
    {
        $errorMessages = [];
        foreach ($violations as $violation) {
            $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return new JsonResponse([
            'errors' => $errorMessages
        ], Response::HTTP_BAD_REQUEST);
    }
}
