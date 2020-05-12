<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO;

final class Category
{
    /** @var string */
    private $name;

    /** @var string */
    private $slug;

    public function __construct(
        string $name,
        string $slug
    ) {
        $this->name = $name;
        $this->slug = $slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name(),
            'slug' => $this->slug(),
        ];
    }
}
