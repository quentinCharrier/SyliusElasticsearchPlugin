<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Article;

use Odiseo\BlogBundle\Model\ArticleInterface;

interface ArticleTransformerInterface
{
    public function transform(ArticleInterface $article): ?string;
}
