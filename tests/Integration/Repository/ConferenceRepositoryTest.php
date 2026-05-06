<?php

namespace App\Tests\Integration\Repository;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConferenceRepositoryTest extends KernelTestCase
{
    public function findBetweenDatesReturnsConferenceAfterStart(): void
    {
        $repository = static::getContainer()->get(ConferenceRepository::class);
    }
}
