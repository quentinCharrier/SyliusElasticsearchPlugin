<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\BlogCategoriesMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Odiseo\BlogBundle\Model\ArticleInterface;

final class BlogArticleCategoriesBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $blogArticleCategoriesResolver;

    /** @var BlogCategoriesMapperInterface */
    private $blogCategoriesMapperInterface;

    public function __construct(BlogCategoriesMapperInterface $blogCategoriesMapperInterface, string $blogArticleCategoriesResolver)
    {
        $this->blogCategoriesMapperInterface = $blogCategoriesMapperInterface;
        $this->blogArticleCategoriesResolver = $blogArticleCategoriesResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ArticleInterface::class,
            function (ArticleInterface $article, Document $document): void {
                $categories = $this->blogCategoriesMapperInterface->mapToUniqueCodes($article);
                $document->set($this->blogArticleCategoriesResolver, $categories);
            }
        );
    }
}
