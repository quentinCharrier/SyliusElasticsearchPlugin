<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper;

use Odiseo\BlogBundle\Model\ArticleInterface;

final class BlogCategoriesMapper implements BlogCategoriesMapperInterface
{
    public function mapToUniqueCodes(ArticleInterface $article): ?array
    {
        $categories = [];

        foreach ($article->getCategories() as $category) {
            $categories[] = $category->getCode();
        }

        return array_values(array_unique($categories));
    }
}
