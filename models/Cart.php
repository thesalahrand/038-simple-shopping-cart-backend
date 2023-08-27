<?php
namespace App\models;

class Cart
{
  private $conn;
  private $tableName = 'cart';

  public $id;
  public $userId;
  public $productId;
  public $quantity;
  public $createdAt;
  public $updatedAt;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT `c`.`id`, `c`.`product_id`, `p`.`name`, `p`.`price`, `p`.`image`, `c`.`quantity` FROM `$this->tableName` `c` JOIN `products` `p` ON `c`.`product_id` = `p`.`id` WHERE `c`.`user_id` = :userId ORDER BY `c`.`updated_at` DESC;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $this->userId);

    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function readById()
  {
    $query = "SELECT `id`, `user_id`, `product_id`, `quantity` FROM `$this->tableName` WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function readByUserAndProduct()
  {
    $query = "SELECT `id`, `user_id`, `product_id`, `quantity` FROM `$this->tableName` WHERE `user_id` = :userId AND `product_id` = :productId;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $this->userId);
    $stmt->bindParam(':productId', $this->productId);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create()
  {
    $this->createdAt = date('Y-m-d H:i:s');
    $this->updatedAt = date('Y-m-d H:i:s');

    $query = "INSERT INTO `$this->tableName`(`user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES(:userId, :productId, :quantity, :createdAt, :updatedAt);";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $this->userId);
    $stmt->bindParam(':productId', $this->productId);
    $stmt->bindParam(':quantity', $this->quantity);
    $stmt->bindParam(':createdAt', $this->createdAt);
    $stmt->bindParam(':updatedAt', $this->updatedAt);

    $stmt->execute();

    $this->id = $this->conn->lastInsertId();

    return $this->readById();
  }

  public function updateQuantity()
  {
    $this->updatedAt = date('Y-m-d H:i:s');

    $query = "UPDATE `$this->tableName` SET `quantity` = :quantity, `updated_at` = :updatedAt WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':quantity', $this->quantity);
    $stmt->bindParam(':updatedAt', $this->updatedAt);
    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $this->readById();
  }

  public function delete()
  {
    $singleCartItem = $this->readById();

    $query = "DELETE FROM `$this->tableName` WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $singleCartItem;
  }
}
?>