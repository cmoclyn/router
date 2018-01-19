<?php

namespace Router;

use Router\Exceptions\RouteException;
use Doctrine\Common\Annotations\{AnnotationReader, FileCacheReader};

/**
 * Class RouteHandler
 *
 * This class parse the file in the controllers directories given,
 * to find all the routes
 *
 * @see addControllersDirectory()
 * @see Annotations\Route
 */
class RouteHandler{
  /**
   * The registered routes
   */
  private $routes = array();

  /**
   * The directories in which we will find the routes
   */
  private $directories = array();




  /**
   * Add a directory in which we will find Route
   *
   * @param string $directory Directory to add
   */
  public function addControllersDirectory(string $directory):void{
    if(!is_dir($directory)){
      throw new RouteException("The directory '$directory' does not exists.", RouteException::DIRECTORY_NOT_EXISTS);
    }
    $this->directories[] = $directory;
  }


  /**
   * Use it to parse all the directories set, find and add Route
   *
   * @see addControllersDirectory()
   * @see Annotations\Route
   */
  public function findRoutes():void{
    $reader = new FileCacheReader(
      new AnnotationReader(),
      __DIR__."/cache",
      $debug = true
    );

    $routes = array();

    foreach($this->directories as $directory){
      foreach(glob("$directory/*.php") as $file){
        $namespace = '';
        $f = fopen($file, 'r');
        while(!feof($f)){
          if(preg_match('/namespace (.*);/', fgets($f), $match)){
            $namespace = $match[1];
            break;
          }
        }
        $class = "$namespace\\".basename($file , '.php');
        $reflClass = new \ReflectionClass($class);
        foreach($reflClass->getMethods() as $method){
          foreach($reader->getMethodAnnotations($method) as $annot){
            if(is_a($annot, 'Annotations\Route')){
              $route = new \Router\Route($annot->name, $annot->pattern, $method->class, $method->name);
            }elseif(is_a($annot, 'Annotations\Parameter')){
              $route->addParameter($annot);
            }elseif(is_a($annot, 'Annotations\Authorization')){
              $route->addAuthorization($annot);
            }
          }
          if(isset($route)){
            $this->addRoute($route);
          }
          unset($route);
        }
      }
    }
  }


  /**
   * Add a route and order by pattern
   *
   * @param \Router\Route $route Route to add
   */
  public function addRoute(\Router\Route $route):void{
    $this->routes[$route->getPattern()] = $route;
  }

  /**
   * Find the route with the given pattern
   *
   * @param  string        $pattern Pattern to match
   * @return Router\Route           The Route founded or throw a RouteException
   */
  public function findRouteByPattern(string $pattern):\Router\Route{
    $this->checkNotEmpty();
    foreach($this->routes as $route){
      if(preg_match($route->getPattern(), $pattern)){
        return $route;
      }
    }
    throw new RouteException("No route find by pattern '$pattern'", RouteException::NO_ROUTE_FOUND);
  }


  /**
   * Find the route with the given name
   *
   * @param  string        $name Name to match
   * @return Router\Route        The Route founded or throw a RouteException
   */
  public function findRouteByName(string $name):\Router\Route{
    $this->checkNotEmpty();
    foreach($this->routes as $route){
      if($route->getName() == $name){
        return $route;
      }
    }
    throw new RouteException("No route find by name '$name'", RouteException::NO_ROUTE_FOUND);
  }

  /**
   * Throw a RouteException if there is no route registered
   */
  private function checkNotEmpty():void{
    if(empty($this->routes)){
      throw new RouteException("", RouteException::NO_ROUTES);
    }
  }
}
