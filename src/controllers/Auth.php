<?php /** @noinspection PhpUnused */


use Tour\config\Constants;
use Tour\helpers\Encryption;
use Tour\helpers\Image;
use Tour\helpers\Validation;
use Tour\models\UserModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->post("/signin", Auth::class . ":login");
$this->post("/signup", Auth::class . ":register");

/**
 * Class Auth
 */
class Auth implements Constants
{
    /**
     * @var Request request
     * @var Response response
     * @var Image image
     * @var Encryption encryption
     * @var UserModel userModel
     * @var Validation validation
     */
    private $request;
    private $response;
    private $image;
    private $encryption;
    private $userModel;
    private $validation;

    /**
     * Auth constructor.
     */
    public function __construct()
    {

        $this->request = new Request();
        $this->response = new Response();
        $this->image = new Image();
        $this->encryption = new Encryption();
        $this->userModel = new UserModel();
        $this->validation = new Validation();
    }

    private function _parse($request, $response)
    {
        $this->request->parse($request);
        $this->response->parse($response);
    }

    public function login($request, $response)
    {

        $this->_parse($request, $response);

        $valid = $this->validation->validate($this->request->get());

        if ( $valid->code != self::SUCCESS) return $this->response->publish(null, $valid->message, $valid->code);

        $user = $this->userModel->login([
            ":email" => $this->request->get("email"),
            ":password" => $this->encryption->password($this->request->get("password"))
        ]);

        switch($user->code) {

            case self::SUCCESS:
                return $this->response->publish($user->data, "Login Success", self::SUCCESS);
                break;
            case self::NOT_FOUND:
                return $this->response->publish(null, "Email has been not registered in another account", self::CONFLICT);
                break;
            case self::FORBIDEN:
                return $this->response->publish(null, "your password is invalid", self::FORBIDEN);
        }
    }

    public function register($request, $response) {

        $this->_parse($request, $response);

        $valid = $this->validation->validate($this->request->get());

        if ( $valid->code != self::SUCCESS) return $this->response->publish(null, $valid->message, $valid->code);

        if (!isset($_FILES["photo"]))
            return $this->response->publish(null, "upload your photo first!", self::ERROR);

        $photo = $this->image->upload($_FILES['photo'], self::PHOTO_PATH, $this->request);

        if ( $photo->code != self::SUCCESS) return $this->response->publish(null, $photo, $photo->code);

        $user = $this->userModel->register([
            ":name"     => $this->request->get("name"),
            ":email"    => $this->request->get("email"),
            ":password" => $this->encryption->password($this->request->get("password")),
            ":photo"    => $this->request->get(self::PHOTO_PATH),
            ":role"     => $this->request->get("role")
        ]);

        if ($user->code == self::CONFLICT)
            return $this->response->publish(null, "Email has been registered in another account", self::CONFLICT);

        return $this->response->publish($user->data, "Success register", self::SUCCESS);
    }
}