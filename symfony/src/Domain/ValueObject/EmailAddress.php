<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class EmailAddress
{
    #[ORM\Column(length: 255)]
    private readonly string $email;

    /**
     * @throws InvalidEmailException
     */
    public function __construct(string $email)
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(sprintf('Adres e-mail "%s" jest nieprawidłowy.', $email));
        }
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->email;
    }

    /**
     * @param self $other
     * @return boolean
     */
    public function equals(self $other): bool
    {
        return $this->email === $other->email;
    }

    /**=
     * @return string
     */
    public function getValue(): string
    {
        return $this->email;
    }
}