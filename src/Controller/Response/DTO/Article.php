<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO;

final class Article
{
    /** @var string */
    private $taxonName;

    /** @var string */
    private $name;

    /** @var string */
    private $slug;

    /** @var string */
    private $image;

    public function __construct(
        string $taxonName,
        string $name,
        string $slug,
        ?string $image
    ) {
        $this->taxonName = $taxonName;
        $this->name = $name;
        $this->slug = $slug;
        $this->image = $image;
    }

    public function taxonName(): string
    {
        return $this->taxonName;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function image(): ?string
    {
        return $this->image;
    }

    public function toArray(): array
    {
        return [
            'taxon_name' => $this->taxonName(),
            'name' => $this->name(),
            'slug' => $this->slug(),
            'image' => $this->image() ?: '',
        ];
    }
}
