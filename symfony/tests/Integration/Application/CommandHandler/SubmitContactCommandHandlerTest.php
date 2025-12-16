<?php

declare(strict_types=1);

namespace App\Tests\Integration\Application\CommandHandler;

use App\Application\Command\SubmitContactCommand;
use App\Domain\ContactSubmission;
use App\Domain\Repository\ContactSubmissionRepositoryInterface; 
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use DateTimeImmutable;

final class SubmitContactCommandHandlerTest extends KernelTestCase
{
    private MessageBusInterface $commandBus;
    private ContactSubmissionRepositoryInterface $verificationRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        $this->commandBus = $container->get('command.bus');
        $this->verificationRepository = $container->get(ContactSubmissionRepositoryInterface::class);
    }
    
    public function testHandlerSavesSubmissionToDatabase(): void
    {
        $initialCount = $this->verificationRepository->count();
        
        $command = new SubmitContactCommand(
            'Repository Test',
            'handler.final.test@domain.com',
            'Message saved by handler in integration test.',
            true
        );

        $this->commandBus->dispatch($command);

        $newCount = $this->verificationRepository->count();
        self::assertSame($initialCount + 1, $newCount, 'Expected one new ContactSubmission in the repository.');
        
        /** @var ContactSubmission|null $savedSubmission */
        $savedSubmission = $this->verificationRepository->findOneBy([
            'fullName' => 'Repository Test'
        ]);

        self::assertNotNull($savedSubmission, 'Entity ContactSubmission should be found in the repository.');
        
        self::assertSame('handler.final.test@domain.com', $savedSubmission->getEmail()->getValue());
        self::assertSame('Message saved by handler in integration test.', $savedSubmission->getMessageContent());
        self::assertTrue($savedSubmission->isPrivacyPolicyAccepted());
        
        self::assertGreaterThan(0, $savedSubmission->getId());
        self::assertInstanceOf(DateTimeImmutable::class, $savedSubmission->getSubmittedAt());
    }
}