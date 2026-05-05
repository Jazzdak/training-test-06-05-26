<?php

namespace App\Factory;

use App\Entity\Volunteering;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Volunteering>
 */
final class VolunteeringFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Volunteering::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Volunteering $volunteering): void {
                $conference = ConferenceFactory::random();
                $volunteering
                    ->setConference($conference)
                    ->setForUser(UserFactory::randomOrCreate())
                    ->setStartAt($conference->getStartAt())
                    ->setEndAt($conference->getEndAt())
                    ;
            })
        ;
    }
}
