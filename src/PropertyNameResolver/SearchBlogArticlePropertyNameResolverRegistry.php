<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyNameResolver;

final class SearchBlogArticlePropertyNameResolverRegistry implements SearchBlogArticlePropertyNameResolverRegistryInterface
{
    /** @var ConcatedNameResolverInterface[] */
    private $propertyNameResolvers = [];

    public function addPropertyNameResolver(ConcatedNameResolverInterface $propertyNameResolver): void
    {
        $this->propertyNameResolvers[] = $propertyNameResolver;
    }

    /**
     * @return ConcatedNameResolverInterface[]
     */
    public function getPropertyNameResolvers(): array
    {
        return $this->propertyNameResolvers;
    }
}
