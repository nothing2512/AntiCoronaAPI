<?php

use Corona\systems\Request;

$this->get("/case/indonesia", Cases::class . ":indonesia");
$this->get("/case/indonesia/province", Cases::class . ":province");
$this->get("/case/list", Cases::class . ":countries");
$this->get("/case/globalCase");

class Cases {
    /**
     * @var Request request
     */
    private $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function indonesia($request, $response) {
        $this->request->parse($request);
    }

    public function province($request, $response) {
        $this->request->parse($request);
    }

    public function countries($request, $response) {
        $this->request->parse($request);
    }

    public function globalCase($request, $response) {
        $this->request->parse($request);
    }
}