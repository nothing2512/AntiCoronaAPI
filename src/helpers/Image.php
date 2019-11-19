<?php


namespace Tour\helpers;


use Tour\config\Constants;
use Tour\systems\Request;

/**
 * Class Image
 * @package Tour\helpers
 */
class Image implements Constants
{

    private $imageType = ["png", "jpg", "jpeg", "bmp"];

    public function upload($file, $path, Request &$request)
    {

        // Check error
        if ($file['error']) return ( object )[
            "code"      => self::ERROR,
            "message"   => "Error Uploading Files"
        ];

        // get files extensions
        $exploder = explode(".", $file['name']);
        $ext = end($exploder);

        // Check Extensions
        if (!in_array($ext, $this->imageType)) return (object) [
            "code"      => self::ERROR,
            "message"   => "not supported image type"
        ];

        // generate files name
        $name = hash('sha256', date("YmdHis") . $exploder[0]) . "." . $ext;

        // Check path
        if (!file_exists(self::STORAGE_PATH . $path)) {
            mkdir(self::STORAGE_PATH . $path);
            copy(self::STORAGE_PATH . "index.php", self::STORAGE_PATH . $path . "/index.php");
        }

        // uploading files
        move_uploaded_file($file['tmp_name'], self::STORAGE_PATH . $path . "/" . $name);

        $request->set($path, $name);

        return ( object )[ "code" => self::SUCCESS ];
    }

    public function get($path, $name)
    {
        return (file_exists(self::STORAGE_PATH . $path . "/" . $name)) ?
            self::STORAGE_URI . $path . "/" . $name : self::STORAGE_URI . "default.png";
    }
}