<?php

namespace App\Factory;

use App\Entity\Conference;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Conference>
 */
final class ConferenceFactory extends PersistentObjectFactory
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
        return Conference::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->realText(30),
            'description' => self::faker()->realText(300),
            'accessible' => self::faker()->boolean(),
            'startAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('now', '2030-12-31')),
            'organizations' => OrganizationFactory::randomRangeOrCreate(1, 3),
            'createdBy' => UserFactory::randomOrCreate(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(Conference $conference): void {
                $conference
                    ->setEndAt(
                        \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween(
                            $conference->getStartAt()->format('Y-m-d'),
                            $conference->getStartAt()->format('Y-m-d').' +2 days'
                        ))
                    );
            })
        ;
    }
}
