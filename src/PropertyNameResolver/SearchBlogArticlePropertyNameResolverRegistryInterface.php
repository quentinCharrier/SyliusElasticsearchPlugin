<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

interface SearchBlogArticlePropertyNameResolverRegistryInterface
{
    public function addPropertyNameResolver(ConcatedNameResolverInterface $propertyNameResolver): void;

    /**
     * @return ConcatedNameResolverInterface[]
     */
    public function getPropertyNameResolvers(): array;
}
