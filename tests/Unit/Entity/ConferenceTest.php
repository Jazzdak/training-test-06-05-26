<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Conference;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConferenceTest extends TestCase
{
    #[Test]
    public function gettersReturnPreviouslySetData(): void
    {
        $conference = (new Conference())
            ->setName('SymfonyCon 2024')
            ->setDescription('The best conference for Symfony developers.')
            ->setAccessible(true)
            ->setPrerequisites('Basic knowledge of Symfony framework.')
            ->setStartAt(new \DateTimeImmutable('2024-09-01 09:00:00'))
            ->setEndAt(new \DateTimeImmutable('2024-09-03 17:00:00'))
        ;

        $this->assertSame('SymfonyCon 2024', $conference->getName());
        $this->assertSame('The best conference for Symfony developers.', $conference->getDescription());
        $this->assertTrue($conference->isAccessible());
        $this->assertSame('Basic knowledge of Symfony framework.', $conference->getPrerequisites());
        $this->assertEquals(new \DateTimeImmutable('2024-09-01 09:00:00'), $conference->getStartAt());
        $this->assertEquals(new \DateTimeImmutable('2024-09-03 17:00:00'), $conference->getEndAt());
    }
}
