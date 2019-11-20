<?php


namespace Tour\models;


use Tour\models\adapters\PackageAdapter;
use Tour\systems\Connection;

class PackageModel
{

    /**
     * @var Connection
     */
    private $db;

    /**
     * @var PackageAdapter
     */
    private $packageAdapter;

    public function __construct()
    {
        $this->db = new Connection();
        $this->packageAdapter = new PackageAdapter();
    }

    public function getDetail($params) {

        $query = "SELECT p.*, ( SELECT COUNT(*) FROM `package_view` WHERE `packageId` = p.packageId AND `userId` = :userId ) as hasView ";
        $query .= "FROM `v_package` p WHERE p.packageId = :packageId";
        $this->db->query($query, $params);
        return $this->db->fetch($this->packageAdapter);
    }

    public function getAll($params) {
        $this->db->query("SELECT * FROM `v_package` WHERE `date` >= :date", $params);
        return $this->db->fetchAll($this->packageAdapter);
    }

    public function search($params) {

        $keyword = "'%${params['keyword']}%'";

        $city = $params["cityId"] == "" ? "" : "AND `packageId` IN ( SELECT `packageId` FROM `package_route` WHERE `cityId` = ${params['cityId']})";

        $price = $params["price"];
        if ($price == "") $price = "";
        elseif ($price == 0) $price = "AND `price` <= 500000";
        else $price = "AND `price` >= 500000";

        $date = "AND `date` >= ${params['date']}";

        $this->db->query("SELECT * FROM `v_package` WHERE `name` LIKE $keyword $city $price $date");
        return $this->db->fetchAll($this->packageAdapter);
    }

    public function getPromo($params) {
        $this->db->query("SELECT * FROM `v_package` WHERE `discount` > 0 AND `date` >= :date", $params);
        return $this->db->fetchAll($this->packageAdapter);
    }

    public function getPopular($params) {
        $this->db->query("SELECT * FROM `v_package` WHERE `date` >= :date ORDER BY `view` DESC", $params);
        return $this->db->fetchAll($this->packageAdapter);
    }

    public function getBest() {
        $this->db->query("SELECT * FROM `v_package` WHERE `booked` > 0 ORDER BY `booked` DESC");
        return $this->db->fetchAll($this->packageAdapter);
    }

    public function add($params) {

        $query = "INSERT INTO `package`(`name`, `date`, `days`, `price`, `stock`, `discount`, `image`) VALUES(:name, :date, :days, :price, :stock, :discount, :image);";
        $this->db->query($query, $params);
        return $this->db->lastInsertId();
    }

    public function edit($params) {
        $query = "UPDATE `package` SET `name` = :name, `date` = :date, `days` = :days, `price` = :price, `stock` = :stock, `discount` = :discount";
        $query .= ", `image` = :image WHERE `packageId` = :packageId";
        $this->db->query($query, $params);
    }

    public function deleteItem($params) {
        $this->db->query("DELETE FROM `package_facilities` WHERE `packageId` = :packageId", $params);
        $this->db->query("DELETE FROM `package_route` WHERE `packageId` = :packageId", $params);
    }

    public function delete($params) {
        $this->db->query("CALL `deletePackage`(:packageId)", $params);
    }

    public function insertFacilities($params) {

        $query = "INSERT INTO `package_facilities`(`packageId`, `name`) VALUES(:packageId, :name)";
        $this->db->query($query, $params);
    }

    public function insertRoute($params) {

        $query = "INSERT INTO `package_route`(`packageId`, `route`, `maps`, `cityId`, `date`) VALUES(:packageId, :route, :maps, :cityId, :date)";
        $this->db->query($query, $params);
    }

    public function getFacilities($params) {

        $this->db->query("SELECT `facilitiesId`, `name` FROM `package_facilities` WHERE `packageId` = :packageId", $params);
        return $this->db->fetchAll();
    }

    public function getRoute($params) {

        $this->db->query("SELECT `route`, `maps`, `city`, `date` FROM `v_package_route` WHERE `packageId` = :packageId", $params);
        return $this->db->fetchAll();
    }

    public function viewPackage($params) {

        $this->db->query("CALL `viewPackage`(:packageId, :userId)", $params);
    }

    public function ratePackage($params) {
        $this->db->query("CALL `ratePackage`(:packageId, :userId, :rate)", $params);
    }
}