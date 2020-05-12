<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Category;

final class CategoriesResponse
{
    /** @var array|Category[] */
    private $categories;

    private function __construct(array $categoriesList)
    {
        $this->categories = $categoriesList;
    }

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public function addCategory(DTO\Category $category): void
    {
        $this->categories[] = $category;
    }

    public function all(): \Traversable
    {
        foreach ($this->categories as $category) {
            yield $category->toArray();
        }
    }

    public function toArray(): array
    {
        return ['categories' => iterator_to_array($this->all())];
    }
}
