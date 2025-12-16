<?php

declare(strict_types=1);

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class ContactSubmissionControllerTest extends WebTestCase
{
    private const API_ENDPOINT = '/api/contact';
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testFullLifecycleSubmitsContactAndRetrievesIt(): void
    {
        $submissionData = [
            'fullName' => 'Test E2E User',
            'email' => 'e2e@domain.com',
            'messageContent' => 'This is an E2E test message.',
            'privacyPolicyAccepted' => true,
        ];

        $this->client->request(
            'POST',
            self::API_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($submissionData)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
        
        $this->client->request('GET', self::API_ENDPOINT);

        self::assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('items', $responseData);
        self::assertGreaterThanOrEqual(1, count($responseData['items']));
        
        $found = false;
        foreach ($responseData['items'] as $item) {
            if ($item['email'] === 'e2e@domain.com') {
                $found = true;
                self::assertSame('Test E2E User', $item['fullName']);
                self::assertArrayHasKey('submittedAt', $item);
                break;
            }
        }
        
        self::assertTrue($found, 'Saved submission was not found in the list retrieved via GET.');
    }

    public function testSubmitInvalidData(): void
    {
        $invalidData = [
            'fullName' => 'OK',
            'email' => 'invalid',
            'messageContent' => 'OK',
            'privacyPolicyAccepted' => false,
        ];
        
        $this->client->request(
            'POST',
            self::API_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invalidData)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        self::assertArrayHasKey('errors', $responseData);
        self::assertArrayHasKey('email', $responseData['errors']);
    }

    public function testSubmitInvalidJson(): void
    {        
        $this->client->request(
            'POST',
            self::API_ENDPOINT,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            'dsfs'
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        self::assertArrayHasKey('error', $responseData);
        self::assertEquals('Malformed JSON or invalid request format.', $responseData['error']);
    }
}