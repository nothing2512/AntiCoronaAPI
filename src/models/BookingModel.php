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

    public function get($params) {
        $this->db->query("SELECT * FROM `v_booking` WHERE `bookingId` = :bookingId", $params);
        return $this->db->fetch($this->bookingAdapter);
    }

    public function delete($params) {
        $this->db->query("CALL `deleteBooking`(:bookingId)", $params);
    }

    public function booking($params) {
        $this->db->query("CALL `booking`(:packageId, :userId, :amount, :payment, :date)", $params);
        return $this->db->fetch()->bookingId;
    }

    public function purchase($params) {
        $this->db->query("CALL `purchase`(:bookingId, :desc)", $params);
    }

    public function getHistory($params) {
        $this->db->query("SELECT * FROM `v_booking` WHERE `userId` = :userId", $params);
        return $this->db->fetchAll($this->bookingAdapter);
    }
}