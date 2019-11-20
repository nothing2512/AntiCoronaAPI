<?php

use Tour\config\Constants;
use Tour\helpers\Encryption;
use Tour\helpers\Image;
use Tour\helpers\Validation;
use Tour\models\UserModel;
use Tour\systems\Request;
use Tour\systems\Response;

$this->get("/{userId}", User::class . ":get");
$this->post("/update", User::class . ":update");

class User implements Constants
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

    public function get($request, $response, $args) {

        $this->_parse($request, $response);

        $user = $this->userModel->getUserById([ ":userId" => $args["userId"]]);

        if ( $user == null )
            return $this->response->publish(null, "User not found", self::NOT_FOUND);

        return $this->response->publish($user->data, "success get user", self::SUCCESS);
    }

    public function update($request, $response, $args) {

        $this->_parse($request, $response);

        $valid = $this->validation->validate($this->request->get(), false);

        if ( $valid->code != self::SUCCESS) return $this->response->publish(null, $valid->message, $valid->code);

        if (isset($_FILES["photo"])) {
            $photo = $this->image->upload($_FILES["photo"], self::PHOTO_PATH, $this->request);
            if ($photo->code != self::SUCCESS) return $this->response->publish(null, $photo->message, $photo->code);
        } else {
            $_photo = $this->request->get("photo");
            $_photo = explode("/", $_photo);
            $this->request->set(self::PHOTO_PATH, end($_photo));
        }

        $userId = $this->request->getAuth()->userId;

        $this->userModel->update([
            ":name"     => $this->request->get("name"),
            ":password" => $this->encryption->password($this->request->get("password")),
            ":photo"    => $this->request->get(self::PHOTO_PATH),
            ":userId"   => $userId
        ]);

        $user = $this->userModel->getUserById([":userId" => $userId]);

        return $this->response->publish($user->data, "Success update user", self::SUCCESS);
    }
}