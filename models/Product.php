<?php
namespace App\models;

class Product
{
  private $conn;
  private $tableName = 'products';

  public $id;
  public $name;
  public $price;
  public $image;
  public $createdAt;
  public $updatedAt;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT `id`, `name`, `price`, `image` FROM `$this->tableName`;";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function readById()
  {
    $query = "SELECT * FROM `$this->tableName` WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }
}
?>