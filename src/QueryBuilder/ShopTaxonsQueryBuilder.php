<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchTaxonsPropertyNameResolverRegistryInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ShopTaxonsQueryBuilder implements QueryBuilderInterface
{
    public const QUERY_KEY = 'query';

    /** @var SearchTaxonsPropertyNameResolverRegistryInterface */
    private $searchTaxonsProperyNameResolverRegistry;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(
        SearchTaxonsPropertyNameResolverRegistryInterface $searchTaxonsProperyNameResolverRegistry,
        LocaleContextInterface $localeContext
    ) {
        $this->searchTaxonsProperyNameResolverRegistry = $searchTaxonsProperyNameResolverRegistry;
        $this->localeContext = $localeContext;
    }

    public function buildQuery(array $data): AbstractQuery
    {
        if (!array_key_exists(self::QUERY_KEY, $data)) {
            throw new \RuntimeException(sprintf('Could not build search products query because there\'s no "query" key in provided data. '.'Got the following keys: %s', implode(', ', array_keys($data))));
        }
        $query = $data[self::QUERY_KEY];
        if (!is_string($query)) {
            throw new \RuntimeException(sprintf('Could not build search products query because the provided "query" is expected to be a string '.'but "%s" is given.', is_object($query) ? get_class($query) : gettype($query)));
        }

        $multiMatch = new MultiMatch();
        $multiMatch->setQuery($query);
        $multiMatch->setFuzziness('AUTO');

        $fields = [];
        foreach ($this->searchTaxonsProperyNameResolverRegistry->getPropertyNameResolvers() as $propertyNameResolver) {
            $fields[] = $propertyNameResolver->resolvePropertyName($this->localeContext->getLocaleCode());
        }
        $multiMatch->setFields($fields);

        $bool = new BoolQuery();
        $bool->addMust($multiMatch);

        return $bool;
    }

    private function addMustIfNotNull(?AbstractQuery $query, BoolQuery $boolQuery): void
    {
        if (null !== $query) {
            $boolQuery->addMust($query);
        }
    }
}
