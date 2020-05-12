<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Article;

final class ArticlesResponse
{
    /** @var array|Article[] */
    private $articles;

    private function __construct(array $itemsList)
    {
        $this->articles = $itemsList;
    }

    public static function createEmpty(): self
    {
        return new self([]);
    }

    public function addArticle(DTO\Article $article): void
    {
        $this->articles[] = $article;
    }

    public function all(): \Traversable
    {
        foreach ($this->articles as $article) {
            yield $article->toArray();
        }
    }

    public function toArray(): array
    {
        return ['articles' => iterator_to_array($this->all())];
    }
}
