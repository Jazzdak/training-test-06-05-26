<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;

class UserTest extends TestCase
{
    #[DataProvider('provideEmails')]
    #[Test]
    public function emailIsValidated(string $email): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator()
        ;

        $errors = $validator->validate($email, [new Email(), new NotBlank()]);
        $this->assertNotCount(0, $errors);
    }

    public static function provideEmails(): iterable
    {
        return [
            'no @' => ['fakeemail.com'],
            'no tld' => ['email@domain'],
            'empty string' => [''],
        ];
    }
}
