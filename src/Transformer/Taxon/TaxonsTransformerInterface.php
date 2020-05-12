<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Taxon;

use Sylius\Component\Core\Model\TaxonInterface;

interface TaxonsTransformerInterface
{
    public function transform(TaxonInterface $taxon): ?string;
}
