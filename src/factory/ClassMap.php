<?php

namespace Lifeboat\Factory;

use Lifeboat\Models;
use Lifeboat\Services;

/**
 * Class ClassMap
 * @package Lifeboat\Factory
 */
class ClassMap {

    const MODELS = [
        'order'                 => Models\Order::class,
        'address'               => Models\Address::class,
        'customer'              => Models\Customer::class,
        'collection'            => Models\Collection::class,
        'page'                  => Models\Page::class,
        'custompage'            => Models\CustomPage::class,
        'deliveryzone'          => Models\DeliveryZone::class,
        'taxcode'               => Models\TaxCode::class,
        'taxzone'               => Models\TaxZone::class,
        'location'              => Models\Location::class,
        'media'                 => Models\Media::class,
        'image'                 => Models\Image::class,
        'product'               => Models\Product::class,
        'productsearchfilter'    => Models\SearchFilter::class,
        'shippingclass'         => Models\ShippingClass::class,
        'producttype'           => Models\ProductType::class
    ];

    const SERVICES = [
        'orders'            => Services\Orders::class,
        'addresses'         => Services\Addresses::class,
        'customers'         => Services\Customers::class,
        'collections'       => Services\Collections::class,
        'pages'             => Services\Pages::class,
        'custom_pages'      => Services\CustomPages::class,
        'delivery_zones'    => Services\DeliveryZones::class,
        'tax_codes'         => Services\TaxCodes::class,
        'locations'         => Services\Locations::class,
        'media'             => Services\Media::class,
        'products'          => Services\Products::class,
        'search_filters'     => Services\SearchFilters::class,
        'shipping_classes'  => Services\ShippingClasses::class,
        'product_types'     => Services\ProductTypes::class
    ];

    const SERVICE_MODEL = [
        Services\Orders::class          => Models\Order::class,
        Services\Addresses::class       => Models\Address::class,
        Services\Customers::class       => Models\Customer::class,
        Services\Collections::class     => Models\Collection::class,
        Services\Pages::class           => Models\Page::class,
        Services\CustomPages::class     => Models\CustomPage::class,
        Services\DeliveryZones::class   => Models\DeliveryZone::class,
        Services\TaxCodes::class        => Models\TaxCode::class,
        Services\Locations::class       => Models\Location::class,
        Services\Media::class           => Models\Media::class,
        Services\Products::class        => Models\Product::class,
        Services\SearchFilters::class   => Models\SearchFilter::class,
        Services\ShippingClasses::class => Models\ShippingClass::class,
        Services\ProductTypes::class    => Models\ProductType::class
    ];
}
