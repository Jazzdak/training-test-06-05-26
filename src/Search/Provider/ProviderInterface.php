<?php

namespace App\Search\Provider;

interface ProviderInterface
{
    public function get(array $data): mixed;
}
