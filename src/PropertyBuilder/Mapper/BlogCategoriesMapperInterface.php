<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use Odiseo\BlogBundle\Model\ArticleInterface;

interface BlogCategoriesMapperInterface
{
    public function mapToUniqueCodes(ArticleInterface $article): ?array;
}
