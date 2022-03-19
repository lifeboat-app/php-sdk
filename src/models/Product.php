<?php

namespace Lifeboat\Models;

/**
 * Class Product
 * @package Lifeboat\Models
 *
 * @todo Implement SearchData objects
 * @todo Implement ProductVariant objects
 *
 * @property string $Vendor
 * @property string $Summary
 * @property string $Description
 * @property string $SKU
 * @property string $SupplierSKU
 * @property float $Price
 * @property float $Discounted
 * @property bool $TrackStock
 * @property string $Condition
 * @property string|null $Option1
 * @property string|null $Option2
 * @property string|null $Option3
 * @property array $Tags
 * @property float $CostOfGoods
 * @property bool $hasVariants
 * @property float $minRetails
 * @property float $maxRetails
 * @property string $Link
 * @property string $URLPrefix
 * @property bool $can_edit
 * @property bool $can_delete
 * @property int $ProductTypeID
 * @property int $TaxCodeID
 * @property int $ShippingClassID
 * @property Image|null $Image
 * @property Media[] $Media
 * @property array $SearchData
 * @property array $Variants
 * @property array $Descriptions
 * @property int $ReviewsCount
 * @property int $Rating
 * @property LifeboatModel[]|null $MetaData
 */
class Product extends Page {

    protected static $casting = [
        'Price'             => 'floatval',
        'Discounted'        => 'floatval',
        'CostOfGoods'       => 'floatval',
        'minRetail'         => 'floatval',
        'maxRetail'         => 'floatval',
        'TrackStock'        => 'boolval',
        'hasVariants'       => 'boolval',
        'can_edit'          => 'boolval',
        'can_delete'        => 'boolval',
        'ProductTypeID'     => 'intval',
        'TaxCodeID'         => 'intval',
        'ShippingClassID'   => 'intval',
        'ReviewsCount'      => 'intval',
        'Rating'            => 'intval'
    ];

}
