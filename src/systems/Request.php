<?php

namespace Corona\systems;

/**
 * Class Request
 * @package Tour\systems
 */
class Request
{
    /**
     * @var object
     */
    private $params;

    /**
     * @var \Slim\Http\Request
     */
    private $request;

    /**
     * Parsing request data
     *
     * @param \Slim\Http\Request $request
     */
    public function parse (\Slim\Http\Request $request ) {

        // Set request
        $this->request = $request;

        // Get params
        $this->params = $request->isPost() ?
            ( object ) $request->getParsedBody() :
            ( object ) $request->getQueryParams();
    }

    /**
     * Set params value
     *
     * @param $key
     * @param $value
     */
    public function set ( $key, $value ) {

        $this->params->$key = $value;
    }

    /**
     * Get param value
     *
     * @param $key
     * @return object|string
     */
    public function get ( $key = null ) {

        return $key == null ?
            $this->params :
            ( isset($this->params->$key) ? $this->params->$key : "");
    }

    /**
     * Remove params value
     *
     * @param $key
     * @noinspection PhpUnused
     */
    public function remove ( $key ) {

        unset ( $this->params->$key );
    }
}