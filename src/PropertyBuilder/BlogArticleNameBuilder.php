<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Odiseo\BlogBundle\Model\ArticleInterface;

final class BlogArticleNameBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $blogArticleNameResolver;

    public function __construct(ConcatedNameResolverInterface $blogArticleNameResolver)
    {
        $this->blogArticleNameResolver = $blogArticleNameResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ArticleInterface::class,
            function (ArticleInterface $article, Document $document): void {
                foreach ($article->getTranslations() as $articleTranslastion) {
                    $propertyName = $this->blogArticleNameResolver->resolvePropertyName($articleTranslastion->getLocale());
                    $document->set($propertyName, $articleTranslastion->getTitle());
                }
            }
        );
    }
}
