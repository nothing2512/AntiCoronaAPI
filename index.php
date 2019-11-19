<?php /** @noinspection ALL */

require_once ('../vendor/autoload.php');
require_once ('./vendor/autoload.php');

use Tour\Tour;

$app = new Tour();
$app->run();