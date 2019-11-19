<?php


namespace Tour\controllers;


use Tour\config\Constants;
use Tour\models\BookingModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->get("/{bookingId}", Booking::class . ":detail");
$this->get("/history", Booking::class . ":history");
$this->post("/booking", Booking::class . ":booking");

class Booking implements Constants
{
    /**
     * @var BookingModel bookingModel
     * @var Request request
     * @var Response response
     */
    private $bookingModel;
    private $request;
    private $response;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->request = new Request();
        $this->response = new Response();
    }

    private function _parse($request, $response) {
        $this->request->parse($request);
        $this->response->parse($response);
    }

    public function detail($request, $response, $args) {}

    public function history($request, $response) {}

    public function booking($request, $response) {}
}