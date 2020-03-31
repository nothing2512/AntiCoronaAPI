<?php

namespace Corona\models;

use Corona\config\Constants;

class NewsModel implements Constants {

    public function getNews($country) {
        $url = self::NEWS . "&apiKey=" . self::NEWSAPI_KEY . "&country=" . $country;

        $_data = file_get_contents($url);

        return json_decode($_data);
    }
}
