<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\FinderInterface;

final class ShopTaxonsFinder implements ShopTaxonsFinderInterface
{
    /** @var QueryBuilderInterface */
    private $shopTaxonsQueryBuilder;

    /** @var FinderInterface */
    private $taxonsFinder;

    public function __construct(
        QueryBuilderInterface $shopTaxonsQueryBuilder,
        FinderInterface $taxonsFinder
    ) {
        $this->shopTaxonsQueryBuilder = $shopTaxonsQueryBuilder;
        $this->taxonsFinder = $taxonsFinder;
    }

    public function find(string $name): ?array
    {
        $data = ['query' => $name];
        $boolQuery = $this->shopTaxonsQueryBuilder->buildQuery($data);
        $query = new Query($boolQuery);

        return $this->taxonsFinder->find($query);
    }
}
