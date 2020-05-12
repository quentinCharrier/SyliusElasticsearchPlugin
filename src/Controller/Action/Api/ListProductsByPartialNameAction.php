<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Api;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\ArticlesResponse;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\CategoriesResponse;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Article;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Category;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Item;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\ItemsResponse;
use BitBag\SyliusElasticsearchPlugin\Finder\BlogArticlesFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\NamedProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopTaxonsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Transformer\Article\ArticleTransformerInterface;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\TransformerInterface;
use BitBag\SyliusElasticsearchPlugin\Transformer\Taxon\TaxonsTransformerInterface;
use Odiseo\BlogBundle\Model\ArticleInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListProductsByPartialNameAction
{
    /** @var NamedProductsFinderInterface */
    private $namedProductsFinder;

    /** @var ShopTaxonsFinderInterface */
    private $shopTaxonsFinder;

    /** @var BlogArticlesFinderInterface */
    private $blogArticlesFinder;

    /** @var TransformerInterface */
    private $productSlugTransformer;

    /** @var TransformerInterface */
    private $productChannelPriceTransformer;

    /** @var TransformerInterface */
    private $productImageTransformer;

    /** @var TaxonsTransformerInterface */
    private $taxonSlugTransformer;

    /** @var ArticleTransformerInterface */
    private $articleSlugTransformer;

    /** @var ArticleTransformerInterface */
    private $articleImageTransformer;

    public function __construct(
        NamedProductsFinderInterface $namedProductsFinder,
        ShopTaxonsFinderInterface $shopTaxonsFinder,
        BlogArticlesFinderInterface $blogArticlesFinder,
        TransformerInterface $productSlugResolver,
        TransformerInterface $productChannelPriceResolver,
        TransformerInterface $productImageResolver,
        TaxonsTransformerInterface $taxonSlugTransformer,
        ArticleTransformerInterface $articleSlugTransformer,
        ArticleTransformerInterface $articleImageTransformer
    ) {
        $this->namedProductsFinder = $namedProductsFinder;
        $this->shopTaxonsFinder = $shopTaxonsFinder;
        $this->blogArticlesFinder = $blogArticlesFinder;
        $this->productSlugTransformer = $productSlugResolver;
        $this->productChannelPriceTransformer = $productChannelPriceResolver;
        $this->productImageTransformer = $productImageResolver;
        $this->taxonSlugTransformer = $taxonSlugTransformer;
        $this->articleSlugTransformer = $articleSlugTransformer;
        $this->articleImageTransformer = $articleImageTransformer;
    }

    public function __invoke(Request $request): Response
    {
        $itemsResponse = ItemsResponse::createEmpty();
        $categoriesResponse = CategoriesResponse::createEmpty();
        $articlesResponse = ArticlesResponse::createEmpty();

        if (null === $request->query->get('query')) {
            return JsonResponse::create($itemsResponse->toArray());
        }

        $products = $this->namedProductsFinder->findByNamePart($request->query->get('query'));
        $categories = $this->shopTaxonsFinder->find($request->query->get('query'));
        $articles = $this->blogArticlesFinder->find($request->query->get('query'));

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            if (null === $productMainTaxon = $product->getMainTaxon()) {
                continue;
            }

            $itemsResponse->addItem(new Item(
                $productMainTaxon->getName(),
                $product->getName(),
                $product->getShortDescription(),
                $this->productSlugTransformer->transform($product),
                $this->productChannelPriceTransformer->transform($product),
                $this->productImageTransformer->transform($product)
            ));
        }

        /** @var TaxonInterface $category */
        foreach ($categories as $category) {
            $categoriesResponse->addCategory(new Category(
                $category->getTranslation()->getName(),
                $this->taxonSlugTransformer->transform($category)
            ));
        }

        /** @var ArticleInterface $article */
        foreach ($articles as $article) {
            $articlesResponse->addArticle(new Article(
                '$article->getCategories()',
                $article->getTranslation()->getTitle(),
                $this->articleSlugTransformer->transform($article),
                $this->articleImageTransformer->transform($article),
            ));
        }

        $response = $itemsResponse->toArray() + $categoriesResponse->toArray() + $articlesResponse->toArray();

        return JsonResponse::create($response);
    }
}
