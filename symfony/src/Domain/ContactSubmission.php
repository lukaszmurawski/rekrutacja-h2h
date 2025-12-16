<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Repository\ContactSubmissionRepositoryInterface;
use App\Domain\ValueObject\EmailAddress;
use App\Domain\Exception\InvalidEmailException;
use App\Domain\Exception\PrivacyPolicyRequiredException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactSubmissionRepositoryInterface::class)]
#[ORM\Table(name: 'contact_submissions')]
class ContactSubmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $fullName;

    #[ORM\Embedded(class: EmailAddress::class, columnPrefix: false)]
    private EmailAddress $email;

    #[ORM\Column(type: Types::TEXT)]
    private string $messageContent;

    #[ORM\Column]
    private bool $privacyPolicyAccepted;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $submittedAt;

    /**
     * @param string $fullName
     * @param EmailAddress $email
     * @param string $messageContent
     * @param boolean $privacyPolicyAccepted
     */
    private function __construct(
        string $fullName,
        EmailAddress $email,
        string $messageContent,
        bool $privacyPolicyAccepted
    ) {
        $this->fullName = $fullName;
        $this->email = $email;
        $this->messageContent = $messageContent;
        $this->privacyPolicyAccepted = $privacyPolicyAccepted;
        $this->submittedAt = new \DateTimeImmutable();
    }

    /**
     * @param string $fullName
     * @param string $email
     * @param string $messageContent
     * @param bool $privacyPolicyAccepted
     * 
     * @throws InvalidEmailException
     * @throws PrivacyPolicyRequiredException
     */
    public static function submit(
        string $fullName,
        string $email,
        string $messageContent,
        bool $privacyPolicyAccepted
    ): self {
        if (!$privacyPolicyAccepted) {
            throw new PrivacyPolicyRequiredException('You must accept the privacy policy to submit the contact form.');
        }

        $emailVo = new EmailAddress($email); 
        
        return new self(
            $fullName, 
            $emailVo, 
            $messageContent, 
            $privacyPolicyAccepted
        );
    }
    
    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getFullName(): string 
    { 
        return $this->fullName; 
    }
    
    /**
     * @return EmailAddress
     */
    public function getEmail(): EmailAddress
    {
        return $this->email;
    }
    
    /**
     * @return string
     */
    public function getMessageContent(): string 
    { 
        return $this->messageContent; 
    }
    
    /**
     * @return boolean
     */
    public function isPrivacyPolicyAccepted(): bool 
    { 
        return $this->privacyPolicyAccepted; 
    }
    
    /**
     * @return \DateTimeImmutable
     */
    public function getSubmittedAt(): \DateTimeImmutable 
    { 
        return $this->submittedAt; 
    }
}