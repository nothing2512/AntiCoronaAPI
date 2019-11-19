<?php


namespace Tour;

use Exception;
use Slim\App;
use Slim\Exception\MethodNotAllowedException;
use Slim\Http\Request;
use Slim\Http\Response;
use Tour\config\Constants;
use Tour\middlewares\Authentication;

/**
 * Class Tour
 * @package Tour
 */
class Tour implements Constants {

    /**
     * @var App
     */
    private $app;

    /**
     * Scool constructor.
     */
    public function __construct(){

        // Set default timeszone
        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Routing Controller Function
     *
     * @param $files
     * @param int $i
     */
    private function _route ( $files, $i = 0 ) {

        // Check files
        if ( sizeof($files) == 0 ) return;

        // Get path
        $_SESSION['path'] = $files[$i];

        // Get controller name
        $exploder = explode(".", $_SESSION['path']);
        $controller = $exploder[0];

        // Get routing name
        $routing = strtolower($controller) == "kelas" ? "class" : strtolower($controller);

        // Make routing group
        $this->app->group('/' . $routing, function () {

            // Get path
            $path = __DIR__ . self::CONTROLLER_PATH . $_SESSION['path'];

            // Requiring Controller
            if (file_exists($path)) /** @noinspection PhpIncludeInspection */ require_once ($path);
        });

        // Make loop
        if ( $i < sizeof($files) - 1 ) $this->_route($files, $i + 1);
    }

    /**
     * Running RestApi
     */
    public function run() {

        // Settings Condiguration
        $configuration = [
            'settings'  => [
                'displayErrorDetails'   => true
            ]
        ];

        // Create Slim Framework
        $this->app = new App($configuration);

        // Check Framework
        $this->app->map( ['GET', 'POST'], '/', function (Request $request,Response $response){

            return $response->withJson([
                "name"          => self::APP,
                "version"       => self::VERSION,
                "description"   => self::DESC
            ]);
        });

        // Get controllers path
        $controllers = scandir(__DIR__ . self::CONTROLLER_PATH);
        $controllers = array_splice($controllers, 2, 10);

        // Routing controllers
        $this->_route($controllers);

        // Cors setup
        $this->app->add(function (Request $request, Response $response, $next) {

            $result = $next ( $request, $response );

            /** @noinspection PhpUndefinedMethodInspection */
            return $result->withHeader ( 'Access-Controll-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Authorization')
                ->withHeader ( 'Content-Type', 'application/json; charset=utf-8');
        });

        $this->app->add(new Authentication());

        // Running App
        try {
            $this->app->run();
        } catch ( Exception $e ) {}
        catch (\Throwable $e) {}
    }
}