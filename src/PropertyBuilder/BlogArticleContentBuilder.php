<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Odiseo\BlogBundle\Model\ArticleInterface;

final class BlogArticleContentBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $blogArticleContentResolver;

    public function __construct(ConcatedNameResolverInterface $blogArticleContentResolver)
    {
        $this->blogArticleContentResolver = $blogArticleContentResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ArticleInterface::class,
            function (ArticleInterface $article, Document $document): void {
                foreach ($article->getTranslations() as $articleTranslastion) {
                    $propertyName = $this->blogArticleContentResolver->resolvePropertyName($articleTranslastion->getLocale());
                    $document->set($propertyName, $articleTranslastion->getContent());
                }
            }
        );
    }
}
