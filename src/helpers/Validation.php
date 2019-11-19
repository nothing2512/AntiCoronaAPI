<?php


namespace Tour\helpers;

use Tour\config\Constants;

/**
 * Class Validation
 * @package Tour\helpers
 */
class Validation implements Constants
{

    public function validate($data, $isEmail = true, $isPassword = true) {

        if ($isEmail) {
            $email = filter_var($data->email, FILTER_VALIDATE_EMAIL);

            if (!$email) return ( object)[
                "message" => "invalid email format",
                "code" => self::ERROR
            ];
        }

        if ($isPassword) {
            if (strlen($data->password) > 16) return ( object )[
                "message" => "password length max: 16",
                "code" => self::ERROR
            ];

            if (strlen($data->password) < 8) return ( object )[
                "message" => "password length min: 8",
                "code" => self::ERROR
            ];
        }

        return ( object ) ["code" => self::SUCCESS];
    }
}