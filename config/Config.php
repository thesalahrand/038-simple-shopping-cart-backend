<?php
namespace App\Config;

class Config
{
  public const TIMEZONE = 'Asia/Dhaka';

  public static function init()
  {
    date_default_timezone_set(self::TIMEZONE);
  }
}
?>