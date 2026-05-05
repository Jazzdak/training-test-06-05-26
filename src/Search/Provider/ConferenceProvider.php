<?php

namespace App\Search\Provider;

use App\Search\Client\ApiConferenceSearch;
use App\Search\Database\DatabaseConferenceSearch;
use App\Search\Transformer\ApiToConferrenceTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\Attribute\Lazy;

class ConferenceProvider implements ProviderInterface
{
    private bool $isOrgOrWebsite;

    public function __construct(
        #[AutowireLocator('app.conference_search')]
        private readonly ContainerInterface $searches,
        #[Lazy]
        private readonly EntityManagerInterface $manager,
        #[Lazy]
        private readonly ApiToConferrenceTransformer $conferrenceTransformer,
        private readonly Security $security,
    ) {
        $this->isOrgOrWebsite = $this->security->isGranted('ROLE_ORGANIZER')
            || $this->security->isGranted('ROLE_WEBSITE');
    }

    public function get(array $data): array
    {
        if (0 === \count($data)) {
            return $this->searches->get(DatabaseConferenceSearch::class)->searchByName();
        }

        if (\count($data) > 1) {
            throw new \InvalidArgumentException();
        }

        $conferences = [];

        foreach ($data as $datum) {
            $conferences = $this->searches->get(DatabaseConferenceSearch::class)->searchByName($datum);

            if (\count($conferences) > 0) {
                continue;
            }

            $apiConferences = $this->searches->get(ApiConferenceSearch::class)->searchByName($datum);

            foreach ($apiConferences as $apiConference) {
                $conferences[] = $conference = $this->conferrenceTransformer->transform($apiConference);
                if ($this->isOrgOrWebsite) {
                    $conference->setCreatedBy($this->security->getUser());
                }
                $this->manager->persist($conference);
            }

            if ($this->isOrgOrWebsite) {
                $this->manager->flush();
            }
        }

        return $conferences;
    }
}
