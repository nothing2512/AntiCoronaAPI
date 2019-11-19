<?php


namespace Tour\models\adapters;


use Tour\config\Constants;
use Tour\helpers\Image;

class PackageAdapter implements Constants
{

    /**
     * @var Image
     */
    private $image;

    const PACKAGE = [
        "packageId"     => 0,
        "name"          => "",
        "date"          => "",
        "days"          => 0,
        "price"         => 0,
        "stock"         => 0,
        "discount"      => 0,
        "image"         => "",
        "view"          => 0
    ];

    public function __construct()
    {
        $this->image = new Image();
    }

    public function __invoke($data)
    {
        $package = (object) array_merge((array) self::PACKAGE, (array) $data);

        $package->image = $this->image->get(self::PACKAGE_PATH, $package->image);
        $package->date = [
            "departure" => $package->date,
            "booking"   => ""
        ];
        $package->price = [
            "normal"    => $package->price,
            "after"     => $this->_countDiscount($package),
            "discount"  => $package->discount
        ];
        unset($package->price);
        unset($package->discount);

        return $package;
    }

    private function _countDiscount($package) {
        return $package->discount > 0 ? $package->price - ($package->price * $package->discount / 100) : $package->price;
    }
}