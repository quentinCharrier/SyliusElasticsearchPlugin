<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

interface BlogArticlesFinderInterface
{
    public function find(string $name): ?array;
}
