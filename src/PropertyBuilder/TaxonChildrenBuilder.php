<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TaxonChildrenBuilder extends AbstractBuilder
{
    /** @var string */
    private $taxonChildrenResolver;

    public function __construct(string $taxonChildrenResolver)
    {
        $this->taxonChildrenResolver = $taxonChildrenResolver;
    }

    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, TaxonInterface::class,
            function (TaxonInterface $taxons, Document $document): void {
                $childrens = [];
                foreach ($taxons->getChildren() as $children) {
                    $childrens[] = $children->getCode();
                }
                $document->set($this->taxonChildrenResolver, $childrens);
            }
        );
    }
}
