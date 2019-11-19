<?php


namespace Tour\models;


use Tour\models\adapters\UserAdapter;
use Tour\systems\Connection;

/**
 * Class UserModel
 * @package Tour\models
 */
class UserModel
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var UserAdapter
     */
    private $userAdapter;

    public function __construct()
    {

        $this->db = new Connection();
        $this->userAdapter = new UserAdapter();
    }

    public function getUserById($params)
    {
        $this->db->query("SELECT * FROM `user` WHERE `userId` = :userId", $params);
        return $this->db->fetch($this->userAdapter);
    }

    public function login($params)
    {
        $this->db->query("CALL `login`(:email, :password)", $params);
        return $this->db->fetch($this->userAdapter);
    }

    public function register($params)
    {
        $this->db->query("CALL `registerUser`(:name, :email, :password, :photo, :role)", $params);
        return $this->db->fetch($this->userAdapter);
    }

    public function update($params)
    {
        $this->db->query("UPDATE `user` SET `name` = :name, `password` = :password, `photo` = :photo WHERE `userId` = :userId", $params);
    }
}