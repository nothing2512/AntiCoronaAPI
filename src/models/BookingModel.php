<?php


namespace Tour\models;


use Tour\models\adapters\BookingAdapter;
use Tour\systems\Connection;

class BookingModel
{

    /**
     * @var Connection
     */
    private $db;

    /**
     * @var BookingAdapter
     */
    private $bookingAdapter;

    public function __construct()
    {
        $this->db = new Connection();
        $this->bookingAdapter = new BookingAdapter();
    }
}