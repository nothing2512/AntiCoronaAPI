<?php


namespace Tour\controllers;


use Tour\config\Constants;
use Tour\helpers\Image;
use Tour\models\PackageModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->get("/detail/{packageId}", Package::class . ":detail");
$this->get("/promo", Package::class . ":promo");
$this->get("/best", Package::class . ":best");
$this->get("/popular", Package::class . ":popular");
$this->post("/search", Package::class . ":search");
$this->post("/rate", Package::class . ":rate");
$this->post("/add", Package::class . ":add");
$this->post("/edit", Package::class . ":edit");
$this->post("/delete", Package::class . ":delete");

class Package implements Constants
{
    /**
     * @var Image image
     * @var PackageModel packageModel
     * @var Request request
     * @var Response response
     */
    private $image;
    private $packageModel;
    private $request;
    private $response;

    public function __construct()
    {
        $this->image = new Image();
        $this->packageModel = new PackageModel();
        $this->request = new Request();
        $this->response = new Response();
    }

    private function _getPackage($packageId) {
        $params = [":packageId" => $packageId];
        $package = $this->packageModel->getDetail([
            ":userId"       => $this->request->getAuth()->userId,
            ":packageId"    => $packageId
        ]);

        if ($package == null) return null;

        $package->route = $this->packageModel->getRoute($params);
        $package->facilities = $this->packageModel->getFacilities($params);

        return $package;
    }

    private function _parse($request, $response) {
        $this->request->parse($request);
        $this->response->parse($response);
    }

    public function detail($request, $response, $args) {

        $this->_parse($request, $response);

        $package = $this->_getPackage($args["packageId"]);

        if ($package == null) return $this->response->publish(null, "package not found", self::NOT_FOUND);

        if ($package->hasView == 0) {
            $this->packageModel->viewPackage([
                ":packageId" => $package->packageId,
                ":userId" => $this->request->getAuth()->userId
            ]);
            $package->hasView = 1;
            $package->view += 1;
        }

        return $this->response->publish($package, "success get package", self::SUCCESS);
    }

    public function promo($request, $response) {

        $this->_parse($request, $response);

        $promo = $this->packageModel->getPromo([":date" => date("Y-m-d")]);

        if (sizeof($promo) == 0) return $this->response->publish(null, "Promo is not yet avaible", self::NOT_FOUND);

        return $this->response->publish($promo, "Success get promo", self::SUCCESS);
    }

    public function best($request, $response) {

        $this->_parse($request, $response);

        $best = $this->packageModel->getBest();

        if (sizeof($best) == 0) return $this->response->publish(null, "best package is not yet avaible", self::NOT_FOUND);

        return $this->response->publish($best, "Success get best", self::SUCCESS);
    }

    public function popular($request, $response) {

        $this->_parse($request, $response);

        $promo = $this->packageModel->getPopular([":date" => date("Y-m-d")]);

        if (sizeof($promo) == 0) return $this->response->publish(null, "No Package avaible", self::NOT_FOUND);

        return $this->response->publish($promo, "Success get promo", self::SUCCESS);
    }

    public function search($request, $response, $args) {

        $this->_parse($request, $response);

        if ($this->request->get("keyword") == "" && $this->request->get("cityId") == "" && $this->request->get("price") == "") {
            $package = $this->packageModel->getAll([ ":date" => date("Y-m-d")]);
        } else {
            $package = $this->packageModel->search([
                "keyword"   => $this->request->get("keyword"),
                "price"     => $this->request->get("price"),
                "cityId"    => $this->request->get("cityId"),
                "date"      => date("Y-m-d")
            ]);
        }

        if (sizeof($package) == 0) return $this->response->publish(null, "Package not found", self::NOT_FOUND);

        return $this->response->publish($package, "Success Search Package", self::SUCCESS);
    }

    public function rate($request, $response) {

        $this->_parse($request, $response);

        $this->packageModel->ratePackage([
            ":packageId"    => $this->request->get("packageId"),
            ":userId"       => $this->request->getAuth()->userId,
            ":rate"         => $this->request->get("rate")
        ]);

        $package = $this->_getPackage($this->request->get("packageId"));

        return $this->response->publish($package, "Success rate package", self::SUCCESS);
    }

    private function _insertFacilities($facility, $i = 0) {

        if ($facility == "") return;

        if (sizeof($facility) == 0) return;

        $this->packageModel->insertFacilities([
            ":packageId"    => $this->request->get("packageId"),
            ":name"         => $facility[$i]
        ]);

        if ($i < sizeof($facility) - 1) $this->_insertFacilities($facility, $i + 1);
    }

    private function _insertRoute($i = 0) {

        $route = (Array) $this->request->get("route");
        $maps = $this->request->get("maps");
        $cityId = $this->request->get("cityId");
        $date = $this->request->get("routeDate");

        if ($route == "") return;

        if (sizeof($route) == 0) return;

        $this->packageModel->insertRoute([
            ":packageId"    => $this->request->get("packageId"),
            ":route"        => $route[$i],
            ":maps"         => $maps[$i],
            ":cityId"       => $cityId[$i],
            ":date"         => $date[$i]
        ]);

        if ($i < sizeof($route) - 1) $this->_insertRoute($i + 1);
    }

    public function add($request, $response) {

        $this->_parse($request, $response);

        $this->image->upload($_FILES["image"], self::PACKAGE_PATH, $this->request);

        $packageId = $this->packageModel->add([
            ":name"     => $this->request->get("name"),
            ":date"     => $this->request->get("date"),
            ":days"     => $this->request->get("days"),
            ":price"    => $this->request->get("price"),
            ":stock"    => $this->request->get("stock"),
            ":discount" => $this->request->get("discount"),
            ":image"    => $this->request->get(self::PACKAGE_PATH)
        ]);

        $this->request->set("packageId", $packageId);

        $this->_insertFacilities($this->request->get("facilities"));
        $this->_insertRoute();

        $package = $this->_getPackage($this->request->get("packageId"));

        return $this->response->publish($package, "Success Add Package", self::SUCCESS);
    }

    public function edit($request, $response) {

        $this->_parse($request, $response);

        if (isset($_FILES["image"])) {
            $image = $this->image->upload($_FILES["image"], self::PACKAGE_PATH, $this->request);
            if ($image->code != self::SUCCESS) return $this->response->publish(null, $image->message, $image->code);
        }
        else {
            $_image = $this->request->get("image");
            $_image = explode("/", $_image);
            $this->request->set(self::PACKAGE_PATH, end($_image));
        }

        $packageId = $this->request->get("packageId");

        $this->packageModel->edit([
            ":name"     => $this->request->get("name"),
            ":date"     => $this->request->get("date"),
            ":days"     => $this->request->get("days"),
            ":price"    => $this->request->get("price"),
            ":stock"    => $this->request->get("stock"),
            ":discount" => $this->request->get("discount"),
            ":image"    => $this->request->get(self::PACKAGE_PATH),
            ":packageId"=> $packageId
        ]);

        $this->packageModel->deleteItem([":packageId" => $packageId]);

        $this->_insertFacilities($this->request->get("facilities"));
        $this->_insertRoute();

        $package = $this->_getPackage($this->request->get("packageId"));

        return $this->response->publish($package, "Success Edit Package", self::SUCCESS);
    }

    public function delete($request, $response) {

        $this->_parse($request, $response);

        $this->packageModel->delete([":packageId" => $this->request->get("packageId")]);

        return $this->response->publish(null, "Success Delete Package", self::SUCCESS);
    }

}