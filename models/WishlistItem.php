<?php
namespace App\models;

class WishlistItem
{
  private $conn;
  private $tableName = 'wishlist_items';

  public $id;
  public $userId;
  public $productId;
  public $createdAt;
  public $updatedAt;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function read()
  {
    $query = "SELECT `w`.`id` AS `wishlist_item_id`, `w`.`product_id`, `p`.`name` AS `product_name`, `p`.`price` AS `product_price`, `p`.`image` AS `product_image` FROM `$this->tableName` `w` JOIN `products` `p` ON `w`.`product_id` = `p`.`id` WHERE `w`.`user_id` = :userId ORDER BY `w`.`updated_at` DESC;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $this->userId);

    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function readById()
  {
    $query = "SELECT `w`.`id` AS `wishlist_item_id`, `w`.`product_id`, `p`.`name` AS `product_name`, `p`.`price` AS `product_price`, `p`.`image` AS `product_image` FROM `$this->tableName` `w` JOIN `products` `p` ON `w`.`product_id` = `p`.`id` WHERE `w`.`id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function readByUserAndProduct()
  {
    $query = "SELECT `w`.`id` AS `wishlist_item_id`, `w`.`product_id`, `p`.`name` AS `product_name`, `p`.`price` AS `product_price`, `p`.`image` AS `product_image` FROM `$this->tableName` `w` JOIN `products` `p` ON `w`.`product_id` = `p`.`id` WHERE `user_id` = :userId AND `product_id` = :productId;";

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

    $query = "INSERT INTO `$this->tableName`(`user_id`, `product_id`, `created_at`, `updated_at`) VALUES(:userId, :productId, :createdAt, :updatedAt);";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':userId', $this->userId);
    $stmt->bindParam(':productId', $this->productId);
    $stmt->bindParam(':createdAt', $this->createdAt);
    $stmt->bindParam(':updatedAt', $this->updatedAt);

    $stmt->execute();

    $this->id = $this->conn->lastInsertId();

    return $this->readById();
  }

  public function delete()
  {
    $singleWishlistItem = $this->readById();

    $query = "DELETE FROM `$this->tableName` WHERE `id` = :id;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':id', $this->id);

    $stmt->execute();

    return $singleWishlistItem;
  }
}
?>
