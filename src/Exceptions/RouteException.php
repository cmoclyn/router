<?php

namespace Router\Exceptions;

use \Exceptions\Exception;

class RouteException extends Exception{
  const NO_ROUTES       = 0;
  const NO_ROUTE_FOUND  = 1;

  public function __construct($message, $value){
    switch($value){
      case self::NO_ROUTES:
        $title  = "No routes have been register";
        $type   = "E";
        break;
      case self::NO_ROUTE_FOUND:
        $title  = "No route found";
        $type   = "E";
        break;
    }

    parent::__construct($title, $message, $type);
  }
}
