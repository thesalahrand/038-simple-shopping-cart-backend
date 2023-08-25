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

  public function readById()
  {
    $query = "SELECT * FROM `$this->tableName` WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function read($userId)
  {
    $query = "SELECT `p`.`id`, `p`.`name`, `p`.`price`, `p`.`image`, (SELECT COUNT(*) FROM `wishlist` `w` WHERE `w`.`product_id` = `p`.`id` AND `w`.`user_id` = :userId) AS `added_to_cart` FROM `$this->tableName` `p`;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $userId);

    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
?>
