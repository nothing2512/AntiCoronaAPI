<?php


namespace Tour\models\adapters;


use Tour\config\Constants;
use Tour\helpers\Image;

class BookingAdapter implements Constants
{
    /**
     * @var Image
     */
    private $image;

    const BOOKING = [
        "bookingId"     => 0,
        "name"          => "",
        "image"         => "",
        "date"          => "",
        "departureDate" => "",
        "bookDate"      => "",
        "days"          => 0,
        "user"          => "",
        "amount"        => 0,
        "originalPrice" => 0,
        "discount"      => 0,
        "price"         => 0,
        "payment"       => ""
    ];

    public function __construct()
    {
        $this->image = new Image();
    }

    public function __invoke($data)
    {
        $booking = ( object ) array_merge((array) self::BOOKING, (array) $data);

        $booking->image = $this->image->get(self::PACKAGE_PATH, $booking->image);

        $booking->price = [
            "normal"    => $booking->originalPrice,
            "after"     => $booking->price,
            "discount"  => $booking->discount
        ];

        $booking->date = [
            "departure" => $booking->departureDate,
            "booking"   => $booking->bookDate
        ];

        unset($booking->originalPrice);
        unset($booking->price);
        unset($booking->discount);
        unset($booking->departureDate);
        unset($booking->bookDate);

        return $booking;
    }
}