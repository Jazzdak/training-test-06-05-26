<?php

namespace App\Search\Transformer;

use App\Entity\Conference;
use App\Search\Provider\OrganizationProvider;

class ApiToConferrenceTransformer implements ApiToEntityTransformerInterface
{
    public function __construct(
        private readonly OrganizationProvider $organizationProvider
    ) {}

    public function transform(array $data): Conference
    {
        $conference = (new Conference())
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setAccessible($data['accessible'])
            ->setPrerequisites($data['prerequisites'])
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
