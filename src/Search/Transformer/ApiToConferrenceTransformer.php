<?php

namespace App\Search\Transformer;

use App\Entity\Conference;
use App\Search\Provider\OrganizationProvider;

class ApiToConferrenceTransformer implements ApiToEntityTransformerInterface
{
    private const KEYS = [
        'name',
        'description',
        'accessible',
        'startDate',
        'endDate',
    ];

    public function __construct(
        private readonly OrganizationProvider $organizationProvider
    ) {}

    public function transform(array $data): Conference
    {
        if (0 < \count(\array_diff(self::KEYS, \array_keys($data)))) {
            throw new \InvalidArgumentException('Missing key from data array.');
        }

        $conference = (new Conference())
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setAccessible($data['accessible'])
            ->setPrerequisites($data['prerequisites'] ?? '')
            ->setStartAt(new \DateTimeImmutable($data['startDate']))
            ->setEndAt(new \DateTimeImmutable($data['endDate']))
            ;

        $organizations = $this->organizationProvider->getOrganizations($data['organizations']);

        foreach ($organizations as $organization) {
            $conference->addOrganization($organization);
        }

        return $conference;
    }
}
