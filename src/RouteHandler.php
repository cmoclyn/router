<?php

namespace Router;

use Router\Exceptions\RouteException;

class RouteHandler{
  private $routes;

  public function __construct(){

  }

  public function addRoute(Route $route){
    $this->routes[$route->getPattern()] = $route;
  }

  public function findRouteByPattern(string $pattern){
    $this->checkNotEmpty();
    foreach($this->routes as $route){
      if(preg_match($route->getPattern(), $pattern)){
        return $route;
      }
    }
    throw new RouteException("No route find by pattern '$pattern'", RouteException::NO_ROUTE_FOUND);
  }

  public function findRouteByName(string $name){
    $this->checkNotEmpty();
    foreach($this->routes as $route){
      if($route->getName() == $name){
        return $route;
      }
    }
    throw new RouteException("No route find by name '$name'", RouteException::NO_ROUTE_FOUND);
  }

  private function checkNotEmpty(){
    if(empty($this->routes)){
      throw new RouteException("", RouteException::NO_ROUTES);
    }
  }
}
