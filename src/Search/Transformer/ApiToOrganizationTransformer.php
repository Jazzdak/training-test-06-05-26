<?php

namespace App\Search\Transformer;

use App\Entity\Organization;
use App\Search\Transformer\ApiToEntityTransformerInterface;

class ApiToOrganizationTransformer implements ApiToEntityTransformerInterface
{

    public function transform(array $data): Organization
    {
        return (new Organization())
            ->setName($data['name'])
            ->setPresentation($data['presentation'] ?? 'No presentation')
            ->setCreatedAt(new \DateTimeImmutable($data['creationDate'] ?? null))
            ;
    }
}
