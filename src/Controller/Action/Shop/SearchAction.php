<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Block\SearchFormEventListener;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\BlogArticlesFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopTaxonsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SearchAction
{
    /** @var EngineInterface */
    private $templatingEngine;

    /** @var PaginatedFinderInterface */
    private $finder;

    /** @var SearchFormEventListener */
    private $searchFormEventListener;

    /** @var RegistryInterface */
    private $facetRegistry;

    /** @var QueryBuilderInterface */
    private $searchProductsQueryBuilder;

    /** @var PaginationDataHandlerInterface */
    private $paginationDataHandler;

    /** @var ShopTaxonsFinderInterface */
    private $shopTaxonsFinder;

    /** @var BlogArticlesFinderInterface */
    private $blogArticleFinder;

    public function __construct(
        EngineInterface $templatingEngine,
        PaginatedFinderInterface $finder,
        SearchFormEventListener $searchFormEventListener,
        RegistryInterface $facetRegistry,
        QueryBuilderInterface $searchProductsQueryBuilder,
        PaginationDataHandlerInterface $paginationDataHandler,
        ShopTaxonsFinderInterface $shopTaxonsFinder,
        BlogArticlesFinderInterface $blogArticleFinder
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->finder = $finder;
        $this->searchFormEventListener = $searchFormEventListener;
        $this->facetRegistry = $facetRegistry;
        $this->searchProductsQueryBuilder = $searchProductsQueryBuilder;
        $this->paginationDataHandler = $paginationDataHandler;
        $this->shopTaxonsFinder = $shopTaxonsFinder;
        $this->blogArticleFinder = $blogArticleFinder;
    }

    public function __invoke(Request $request): Response
    {
        $template = $request->get('template');
        $form = $this->searchFormEventListener->getForm();
        $form->handleRequest($request);

        $results = null;
        $taxons = null;
        $articles = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();

            $boolQuery = new Query\BoolQuery();
            $boolQuery->addMust(
                $this->searchProductsQueryBuilder->buildQuery(['query' => $search->getBox()->getQuery()])
            );

            if ($search->getFacets()) {
                foreach ($search->getFacets() as $facetId => $selectedBuckets) {
                    if (!$selectedBuckets) {
                        continue;
                    }
                    $facet = $this->facetRegistry->getFacetById($facetId);
                    $boolQuery->addFilter($facet->getQuery($selectedBuckets));
                }
            }

            $query = new Query($boolQuery);

            $results = $this->finder->findPaginated($query);
            $taxons = $this->shopTaxonsFinder->find($search->getBox()->getQuery());
            $articles = $this->blogArticleFinder->find($search->getBox()->getQuery());

            $paginationData = $this->paginationDataHandler->retrieveData($request->query->all());
            $results->setCurrentPage($paginationData[PaginationDataHandlerInterface::PAGE_INDEX]);
            $results->setMaxPerPage($paginationData[PaginationDataHandlerInterface::LIMIT_INDEX]);
        }

        return $this->templatingEngine->renderResponse(
            $template, [
                'results' => $results,
                'searchForm' => $form->createView(),
                'taxons' => $taxons,
                'articles' => $articles,
            ]
        );
    }
}
