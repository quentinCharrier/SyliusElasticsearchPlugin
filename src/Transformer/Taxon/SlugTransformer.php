<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Taxon;

use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\Routing\RouterInterface;

final class SlugTransformer implements TaxonsTransformerInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function transform(TaxonInterface $taxon): ?string
    {
        if (null === $taxon->getTranslation()->getSlug()) {
            return null;
        }

        return $this->router->generate('bitbag_sylius_elasticsearch_plugin_shop_list_products', ['slug' => $taxon->getTranslation()->getSlug()]);
    }
}
