<?php /** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUnused */

use Corona\models\CaseModel;
use Corona\systems\Request;

$this->get("/indonesia", Cases::class . ":indonesia");
$this->get("/indonesia/province", Cases::class . ":province");
$this->get("/list", Cases::class . ":countries");
$this->get("/global", Cases::class . ":globalCase");

class Cases {

    /**
     * @var Request request
     */
    private $request;

    /**
     * @var CaseModel caseModel
     */
    private $caseModel;

    public function __construct() {
        $this->request = new Request();
        $this->caseModel = new CaseModel();
    }

    public function indonesia($request, $response) {
        $this->request->parse($request);

        $data = $this->caseModel->getIndonesianCase();

        return $response->withJSON($data);
    }

    public function province($request, $response) {
        $this->request->parse($request);

        $data = $this->caseModel->getProvinceCase();

        return $response->withJSON($data);
    }

    public function countries($request, $response) {
        $this->request->parse($request);

        $data = $this->caseModel->getCountriesCase();

        return $response->withJSON($data);
    }

    public function globalCase($request, $response) {
        $this->request->parse($request);

        $data = $this->caseModel->getGlobalCase();

        return $response->withJSON($data);
    }
}