<?php

namespace App\Search\Database;

use App\Repository\ConferenceRepository;
use App\Search\Interface\ConferenceSearchInterface;

class DatabaseConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function searchByName(?string $name = null): array
    {
        if (null === $name) {
            return $this->repository->findAll();
        }

        return $this->repository->findLikeName($name);
    }
}
