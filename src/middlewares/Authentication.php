<?php


namespace Tour\middlewares;


use Tour\config\Constants;
use Tour\systems\Request;
use Tour\systems\Response;

/**
 * Class Authentication
 * @package Tour\middlwares
 */
class Authentication implements Constants
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct() {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function __invoke($request, $response, $next) {

        $this->request->parse($request);
        $this->response->parse($response);

        $auth = $this->request->getAuth();

        if ($auth->apiKey == "")
            return $this->response->publish(null, "No Api Key Found", self::FORBIDEN);

        if ($auth->apiKey != self::API_KEY)
            return $this->response->publish(null, "Invalid Api Key", self::FORBIDEN);

        return $next($request, $response);
    }
}