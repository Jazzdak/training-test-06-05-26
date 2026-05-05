<?php

namespace App\Search\Interface;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.conference_search')]
interface ConferenceSearchInterface
{
    public function searchByName(?string $name = null): array;
}
