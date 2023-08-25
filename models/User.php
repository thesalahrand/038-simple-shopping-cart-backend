<?php
namespace App\models;

class User
{
  private $conn;
  private $tableName = 'users';

  public $id;
  public $username;
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

  public function readByUsername()
  {
    $query = "SELECT * FROM `$this->tableName` WHERE `username` = :username;";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':username', $this->username);

    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function create()
  {
    $this->createdAt = date('Y-m-d H:i:s');
    $this->updatedAt = date('Y-m-d H:i:s');

    $query = "INSERT INTO `$this->tableName`(`username`, `created_at`, `updated_at`) VALUES(:username, :createdAt, :updatedAt);";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':createdAt', $this->createdAt);
    $stmt->bindParam(':updatedAt', $this->updatedAt);

    $stmt->execute();

    $this->id = $this->conn->lastInsertId();

    return $this->readById();
  }
}
?>
