<?php


namespace Tour\controllers;


use Tour\config\Constants;
use Tour\models\PackageModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->get("/{packageId}", Package::class . ":detail");
$this->get("/promo}", Package::class . ":promo");
$this->get("/best", Package::class . ":best");
$this->get("/popular", Package::class . ":popular");
$this->get("/search/{query}", Package::class . ":search");
$this->post("/add", Package::class . ":add");
$this->post("/edit", Package::class . ":edit");
$this->post("/delete", Package::class . ":delete");

class Package implements Constants
{
    /**
     * @var PackageModel packageModel
     * @var Request request
     * @var Response response
     */
    private $packageModel;
    private $request;
    private $response;

    public function __construct()
    {
        $this->packageModel = new PackageModel();
        $this->request = new Request();
        $this->response = new Response();
    }

    private function _parse($request, $response) {
        $this->request->parse($request);
        $this->response->parse($response);
    }

    public function detail($request, $response, $args) {}

    public function promo($request, $response) {}

    public function best($request, $response) {}

    public function popular($request, $response) {}

    public function search($request, $response, $args) {}

    public function add($request, $response) {}

    public function edit($request, $response) {}

    public function delete($request, $response) {}

}