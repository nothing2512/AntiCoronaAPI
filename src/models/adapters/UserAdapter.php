<?php


namespace Tour\models\adapters;


use Tour\config\Constants;
use Tour\helpers\Image;

/**
 * Class UserAdapter
 * @package Tour\models\adapters
 */
class UserAdapter implements Constants
{

    /**
     * @var Image
     */
    private $image;

    const USER = [
        "userId"        => 0,
        "name"          => "",
        "email"         => "",
        "password"      => "",
        "photo"         => "",
        "role"          => 0,
        "codes"         => self::SUCCESS
    ];

    /**
     * UserAdapter constructor.
     */
    public function __construct() {

        $this->image = new Image();
    }

    /**
     * @param $user
     * @return object
     */
    public function __invoke($user) {

        // Set user
        $user = ( object ) array_merge((array) self::USER, (array) $user);

        // Set image
        $user->photo = $this->image->get(self::PHOTO_PATH, $user->photo);

        // Set Codes
        $codes = isset($user->codes) ? $user->codes : self::SUCCESS;
        unset($user->codes);

        return ( object ) [
            "data"      => $user,
            "code"      => $codes
        ];
    }
}