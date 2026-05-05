<?php

namespace App\Search\Decorator;

use App\Search\Interface\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsDecorator(ConferenceSearchInterface::class)]
readonly class CacheableConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        protected ConferenceSearchInterface $inner,
        protected CacheInterface $cache,
        protected SluggerInterface $slugger,
    ) {}

    public function searchByName(?string $name = null): array
    {
        $slug = $this->slugger->slug($name);

        return $this->cache->get($slug, function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600);

            return $item->set($this->inner->searchByName($name))->get();
        });
    }
}
