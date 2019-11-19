<?php

namespace Tour\systems;

use Tour\config\Constants;

/**
 * Class Response
 * @package Tour\systems
 */
class Response implements Constants
{

    /**
     * @var \Slim\Http\Response
     */
    private $response;

    public function parse ($response ) {

        $this->response = $response;
    }

    /**
     * Publishing data
     *
     * @param null $data
     * @param string $message
     * @param int $code
     * @return false|string
     */
    public function publish ( $data = null, $message = "", $code = self::SUCCESS ) {

        $result = [];

        $result['status'] = $code == self::SUCCESS;
        $result['message'] = $message;
        $result['code'] = $code;
        $result['data'] = $data;

        return $this->response->withJson($result);
    }
}