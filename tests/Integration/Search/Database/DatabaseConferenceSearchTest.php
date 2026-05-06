<?php

namespace App\Tests\Integration\Search\Database;

use App\Entity\Conference;
use App\Factory\ConferenceFactory;
use App\Search\Database\DatabaseConferenceSearch;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class DatabaseConferenceSearchTest extends KernelTestCase
{
    #[Test]
    #[TestDox('searchByName returns findAll when null is passed')]
    public function searchByNameReturnsFindAllWithoutName(): void
    {
        ConferenceFactory::createMany(10);
        /** @var DatabaseConferenceSearch $search */
        $search = static::getContainer()->get(DatabaseConferenceSearch::class);

        $results = $search->searchByName();

        $this->assertCount(10, $results);
        $this->assertInstanceOf(Conference::class, $results[0]);
    }

    #[Test]
    #[TestDox('searchByName searches with LIKE when name is passed')]
    public function searchByNameSearchesWithLikeWhenNameIsPassed(): void
    {
        ConferenceFactory::createMany(10);
        ConferenceFactory::createMany(3, static function (int $key) {
            return ['name' => "Some Symfony Conference $key"];
        });
        /** @var DatabaseConferenceSearch $search */
        $search = static::getContainer()->get(DatabaseConferenceSearch::class);

        $results = $search->searchByName('Symfony');

        $this->assertCount(3, $results);
        $this->assertInstanceOf(Conference::class, $results[0]);
        $this->assertSame('Some Symfony Conference 1', $results[0]->getName());

    }
}
