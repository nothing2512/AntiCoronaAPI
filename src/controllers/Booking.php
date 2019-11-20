<?php


namespace Tour\controllers;


use Tour\config\Constants;
use Tour\models\BookingModel;
use Tour\models\PackageModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->get("/detail/{bookingId}", Booking::class . ":detail");
$this->get("/history", Booking::class . ":history");
$this->post("", Booking::class . ":booking");
$this->post("/purchase", Booking::class . ":purchase");

class Booking implements Constants
{
    /**
     * @var BookingModel bookingModel
     * @var PackageModel packageModel
     * @var Request request
     * @var Response response
     */
    private $bookingModel;
    private $packageModel;
    private $request;
    private $response;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->packageModel = new PackageModel();
        $this->request = new Request();
        $this->response = new Response();
    }

    private function _detail() {

        $booking = $this->bookingModel->get([":bookingId" => $this->request->get("bookingId")]);

        if ($booking == null) return null;

        $params = [":packageId" => $booking->packageId];

        $booking->route = $this->packageModel->getRoute($params);
        $booking->facilities = $this->packageModel->getFacilities($params);

        return $booking;
    }

    private function _parse($request, $response) {
        $this->request->parse($request);
        $this->response->parse($response);
    }

    private function _checkExpired($booking) {

        if ($booking == null) return (object) [
            "code"      => self::NOT_FOUND,
            "message"   => "booking detail not found"
        ];

        if ($booking->status == 0) {
            $package = $this->packageModel->getDetail([
                ":userId"   => $this->request->getAuth()->userId,
                ":packageId" => $booking->packageId
            ]);

            if ($package->stock < $booking->amount) {

                $this->bookingModel->delete(["bookingId" => $booking->bookingId]);

                return (object) [
                    "code"      => self::ERROR,
                    "message"   => "ticket has been expired, please booking again"
                ];
            }
        }

        return (object) ["code" => self::SUCCESS];
    }

    public function detail($request, $response, $args) {

        $this->_parse($request, $response);
        $this->request->set("bookingId", $args["bookingId"]);

        $booking = $this->_detail();

        $isExpired = $this->_checkExpired($booking);

        if ($isExpired->code != self::SUCCESS) return $this->response->publish(null, $isExpired->message, $isExpired->code);

        return $this->response->publish($booking, "Success get booking", self::SUCCESS);
    }

    public function history($request, $response) {

        $this->_parse($request, $response);

        $history = $this->bookingModel->getHistory([":userId" => $this->request->getAuth()->userId]);

        if (sizeof($history) == 0) return $this->response->publish(null, "you doesnt have history yet", self::NOT_FOUND);

        return $this->response->publish($history, "Success get history", self::SUCCESS);
    }

    public function booking($request, $response) {

        $this->_parse($request, $response);

        $package = $this->packageModel->getDetail([
            ":userId"   => $this->request->getAuth()->userId,
            ":packageId" => $this->request->get("packageId")
        ]);

        if ($package == null) return $this->response->publish(null, "Package not found", self::NOT_FOUND);

        if ($package->stock < $this->request->get("amount"))
            return $this->response->publish(null, "the emount exceeds stock", self::ERROR);

        $bookingId = $this->bookingModel->booking([
            ":packageId"    => $this->request->get("packageId"),
            ":userId"       => $this->request->getAuth()->userId,
            ":amount"       => $this->request->get("amount"),
            ":payment"      => $this->request->get("payment"),
            ":date"        => date("Y-m-d H:i:s")
        ]);

        $this->request->set("bookingId", $bookingId);

        $booking = $this->_detail();

        return $this->response->publish($booking, "Success booking package", self::SUCCESS);
    }

    public function purchase($request, $response) {

        $this->_parse($request, $response);

        $booking = $this->_detail();

        $isExpired = $this->_checkExpired($booking);

        if ($isExpired->code != self::SUCCESS) return $this->response->publish(null, $isExpired->message, $isExpired->code);

        if ($booking->status == 1) return $this->response->publish(null, "This ticket has been purchased", self::NOT_FOUND);

        $this->bookingModel->purchase([
            ":bookingId"    => $booking->bookingId,
            ":desc"         => $this->request->get("description")
        ]);

        $booking = $this->_detail();

        return $this->response->publish($booking, "Success purchase ticket", self::SUCCESS);
    }
}