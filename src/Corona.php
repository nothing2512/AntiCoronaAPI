<?php


namespace Corona;


use Corona\config\Constants;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use \Exception;
use \Throwable;

class Corona implements Constants {

    /**
     * @var App
     */
    private $app;

    function __construct()
    {
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _route($files, $i = 0) {

        if (sizeof($files) == 0 ) return;

        $_SESSION['path'] = $files[$i];

        $exploder = explode(".", $_SESSION['path']);
        $controller = $exploder[0];

        $route = strtolower($controller);

        $this->app->group("/" . $route, function(){

            $path = __DIR__ . self::CONTROLLER_PATH . $_SESSION['path'];

            if (file_exists($path)) /** @noinspection PhpIncludeInspection */ require_once ($path);
        });

        if ( $i < sizeof($files) - 1 ) $this->_route($files, $i + 1);
    }

    /** @noinspection PhpUnusedParameterInspection */
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
        array_shift($controllers);
        array_shift($controllers);

        // Routing controllers
        $this->_route($controllers);

        // Cors setup
        $this->app->add(function (Request $request, Response $response, $next) {

            $result = $next ( $request, $response );

            /** @noinspection PhpUndefinedMethodInspection */
            return $result->withHeader ( 'Access-Controll-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Authorization')
                ->withHeader ( 'Content-Type', 'application/json; charset=utf-8');
        });

        // Running App
        try {
            $this->app->run();
        } catch ( Exception $e ) {}
        catch (Throwable $e) {}
    }
}