<?php

namespace App\Tests\Unit\Search\Transformer;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Search\Provider\OrganizationProvider;
use App\Search\Transformer\ApiToConferrenceTransformer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ApiToConferrenceTransformerTest extends TestCase
{
    #[Test]
    public function transformReturnsConferenceWithProperlySetData(): void
    {
        // Arrange
        $data = [
            'name' => 'SymfonyCon 2026',
            'description' => 'The best event for Symfony developers',
            'accessible' => true,
            'prerequisites' => 'Basic Symfony knowledge.',
            'startDate' => '2026-11-26 09:00:00',
            'endDate' => '2026-11-27 17:00:00',
            'organizations' => [
                [
                    'name' => 'Symfony',
                    'creationDate' => '2016-10-25',
                ]
            ]
        ];
        /** @var MockObject|OrganizationProvider $mockProvider */
        $mockProvider = $this->createStub(OrganizationProvider::class);
        $mockProvider
            ->method('getOrganizations')
            ->willReturn([
                (new Organization())
                    ->setName('Symfony')
                    ->setPresentation('The Symfony organization')
                    ->setCreatedAt(new \DateTimeImmutable('2016-10-25'))
            ]);
        $transformer = new ApiToConferrenceTransformer($mockProvider);

        // Act
        $conference = $transformer->transform($data);

        // Assert
        $this->assertInstanceOf(Conference::class, $conference);
        $this->assertSame($data['name'], $conference->getName());
        $this->assertSame($data['description'], $conference->getDescription());
        $this->assertSame($data['accessible'], $conference->isAccessible());
        $this->assertSame($data['prerequisites'], $conference->getPrerequisites());
        $this->assertEquals(new \DateTimeImmutable($data['startDate']), $conference->getStartAt());
        $this->assertEquals(new \DateTimeImmutable($data['endDate']), $conference->getEndAt());
        $this->assertSame('Symfony', $conference->getOrganizations()->first()?->getName());
    }

    #[Test]
    public function transformThrowsOnIncompleteData(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing key from data array.');

        $data = [
            'name' => 'SymfonyCon 2026',
            'description' => 'The best event for Symfony developers',
            'accessible' => true,
        ];
        $transformer = new ApiToConferrenceTransformer(
            $this->createStub(OrganizationProvider::class)
        );

        $conference = $transformer->transform($data);
    }
}
