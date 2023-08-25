<?php
namespace App\Config;

class Config
{
  public static $rootDir;
  public const TIMEZONE = 'Asia/Dhaka';

  public static function init()
  {
    self::$rootDir = $_ENV['ROOT_DIR'];
    date_default_timezone_set(self::TIMEZONE);
  }
}
?>
