<?php


namespace Tour\helpers;


use Tour\config\Constants;

/**
 * Class Encryption
 * @package Tour\helpers
 */
class Encryption implements Constants
{

    /**
     * Generate password
     *
     * @param $password
     * @return string
     */
    public function password ( $password ) {

        return md5 ( self::ENC_KEY . $password . md5(self::ENC_KEY));
    }

    /**
     * Generate pin
     *
     * @param int $digits
     * @return string
     */
    public function pin ( $digits = self::MAX_PIN ) {

        $pad = '0';
        $pow = pow(10, $digits) - 1;
        $vCode = rand(0, $pow);

        return str_pad($vCode, $digits, $pad, STR_PAD_LEFT);
    }
}