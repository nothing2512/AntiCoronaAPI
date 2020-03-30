<?php /** @noinspection PhpUnused */
/** @noinspection PhpUndefinedMethodInspection */

/** @noinspection PhpUnusedParameterInspection */

use Corona\config\Constants;
use Corona\systems\Request;

$this->get("", Faqs::class . ":getFaqs");

class Faqs implements Constants {

    /**
     * @var Request request
     */
    private $request;

    public function __construct() {
        $this->request = new Request();
    }

    public function getFaqs($request, $response) {

        $this->request->parse($request);

        $filename = $this->request->get("lang") == "eng" ? "faqs_eng.json" : "faqs.json";

        $path = str_replace("controllers", self::JSON_PATH . $filename, __DIR__);
        $data = file_get_contents($path);
        return $response->withJSON(json_decode($data));
    }
}