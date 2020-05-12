<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TaxonDescriptionBuilder extends AbstractBuilder
{
    /** @var ConcatedNameResolverInterface */
    private $taxonDescriptionResolver;

    public function __construct(ConcatedNameResolverInterface $taxonDescriptionResolver)
    {
        $this->taxonDescriptionResolver = $taxonDescriptionResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, TaxonInterface::class,
            function (TaxonInterface $taxons, Document $document): void {
                foreach ($taxons->getTranslations() as $taxonsTranslation) {
                    $propertyName = $this->taxonDescriptionResolver->resolvePropertyName($taxonsTranslation->getLocale());
                    $document->set($propertyName, $taxonsTranslation->getDescription());
                }
            }
        );
    }
}
