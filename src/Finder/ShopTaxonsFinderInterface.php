<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

interface ShopTaxonsFinderInterface
{
    public function find(string $name): ?array;
}
