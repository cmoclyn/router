<?php

namespace Router\Exceptions;

use \Exceptions\Exception;

class SecurityException extends Exception{
  const UNAUTHORIZED          = 0;

  public function __construct($message, $value){
    switch($value){
      case self::UNAUTHORIZED:
        $title  = "Unauthorized to execute the route";
        $type   = "E";
        break;
    }

    parent::__construct($title, $message, $type);
  }
}
