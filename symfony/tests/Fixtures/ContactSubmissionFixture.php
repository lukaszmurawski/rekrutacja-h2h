<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Domain\ContactSubmission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ContactSubmissionFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $submission1 = ContactSubmission::submit(
            'Alicja Stara',
            'alicia.stara@wp.pl',
            'Zgłoszenie najstarsze.',
            true
        );
        
        $this->setSubmittedAt($submission1, '-1 day'); 
        $manager->persist($submission1);

        $submission2 = ContactSubmission::submit(
            'Barbara Średnia',
            'barbara@gmail.com',
            'Zgłoszenie środkowe.',
            true
        );
        $this->setSubmittedAt($submission2, '-1 hour');
        $manager->persist($submission2);

        $submission3 = ContactSubmission::submit(
            'Celina Nowa',
            'celina@o2.pl',
            'Zgłoszenie najnowsze.',
            true
        );
        $manager->persist($submission3);

        $manager->flush();
    }
    
    private function setSubmittedAt(ContactSubmission $submission, string $dateTime): void
    {
        $reflection = new \ReflectionObject($submission);
        $property = $reflection->getProperty('submittedAt');
        $property->setAccessible(true);
        $property->setValue($submission, new \DateTimeImmutable($dateTime));
    }
}