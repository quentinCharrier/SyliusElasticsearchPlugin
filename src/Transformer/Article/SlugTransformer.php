<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Article;

use Odiseo\BlogBundle\Model\ArticleInterface;
use Symfony\Component\Routing\RouterInterface;

final class SlugTransformer implements ArticleTransformerInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function transform(ArticleInterface $article): ?string
    {
        if (null === $article->getTranslation()->getSlug()) {
            return null;
        }

        return $this->router->generate('odiseo_sylius_blog_plugin_shop_article_show', ['slug' => $article->getTranslation()->getSlug()]);
    }
}
