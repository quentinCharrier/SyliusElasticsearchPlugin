<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyValueResolver;

use Sylius\Component\Attribute\Model\AttributeValueInterface;

interface AttributeValueResolverInterface
{
    /**
     * @param AttributeValueInterface $attributeValue
     *
     * @return string|bool|int
     */
    public function resolve(AttributeValueInterface $attributeValue);
}