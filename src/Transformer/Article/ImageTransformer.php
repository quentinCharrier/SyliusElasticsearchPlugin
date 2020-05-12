<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Article;

use Liip\ImagineBundle\Service\FilterService;
use Odiseo\BlogBundle\Model\ArticleInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ImageTransformer implements ArticleTransformerInterface
{
    private const SYLIUS_THUMBNAIL_TYPE = 'thumbnail';
    private const SYLIUS_THUMBNAIL_FILTER = 'sylius_shop_product_tiny_thumbnail';

    /** @var FilterService */
    private $imagineFilter;

    /** @var Filesystem */
    private $fs;

    public function __construct(FilterService $imagineFilter, Filesystem $fs)
    {
        $this->imagineFilter = $imagineFilter;
        $this->fs = $fs;
    }

    public function transform(ArticleInterface $article): ?string
    {
        $articleThumbnails = $article->getImages();

        if ($articleThumbnails->isEmpty()) {
            return null;
        }

        /** @var ImageInterface $productImage */
        $articleImage = $articleThumbnails->first();

        return $this->imagineFilter->getUrlOfFilteredImage($articleImage->getPath(), self::SYLIUS_THUMBNAIL_FILTER);
    }
}
