<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        #[Autowire(env: 'DEFAULT_PASSWORD')]
        private readonly string $defaultPassword,
    )
    {
    }

    #[\Override]
    public static function class(): string
    {
        return User::class;
    }

    public function email(string $email): static
    {
        return $this->with(['email' => $email]);
    }

    public function roles(array|string $roles): static
    {
        $roles = \is_array($roles) ? $roles : [$roles];

        return $this->with(['roles' => $roles]);
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
            'email' => self::faker()->email(),
            'roles' => [self::faker()->randomElement(['ROLE_USER', 'ROLE_WEBSITE', 'ROLE_VOLUNTEER'])],
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                $user
                    ->setPassword($this->hasher->hashPassword($user, $this->defaultPassword))
                    ->setApikey()
                ;
            })
        ;
    }
}
