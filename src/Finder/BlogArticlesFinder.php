<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\FinderInterface;

final class BlogArticlesFinder implements BlogArticlesFinderInterface
{
    /** @var QueryBuilderInterface */
    private $blogArticlesQueryBuilder;

    /** @var FinderInterface */
    private $blogArticlesFinder;

    public function __construct(
        QueryBuilderInterface $blogArticlesQueryBuilder,
        FinderInterface $blogArticlesFinder
    ) {
        $this->blogArticlesQueryBuilder = $blogArticlesQueryBuilder;
        $this->blogArticlesFinder = $blogArticlesFinder;
    }

    public function find(string $name): ?array
    {
        $data = ['query' => $name];
        $boolQuery = $this->blogArticlesQueryBuilder->buildQuery($data);
        $query = new Query($boolQuery);

        return $this->blogArticlesFinder->find($query);
    }
}
