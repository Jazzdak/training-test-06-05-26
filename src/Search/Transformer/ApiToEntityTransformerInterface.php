<?php

namespace App\Search\Transformer;

interface ApiToEntityTransformerInterface
{
    public function transform(array $data): object;
}
