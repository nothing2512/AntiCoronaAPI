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
}