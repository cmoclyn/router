<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Router\RouteHandler;
use Router\Route;
use Router\Exceptions\RouteException;
use Router\Exceptions\SecurityException;
use Doctrine\Common\Annotations\{AnnotationReader, FileCacheReader, AnnotationRegistry};

require_once 'Controllers/ControllerExample.php';

class RouteHandlerTest extends TestCase{


  /**
   * @covers Router\RouteHandler::addControllersDirectory
   * @covers Router\RouteHandler::findRoutes
   * @covers Router\Route::__construct
   * @covers Router\Route::addAuthorization
   * @covers Router\Route::addParameter
   * @covers Router\Route::checkAuthorization
   * @covers Router\Route::call
   * @covers Router\Route::getController
   * @covers Router\Route::getMethod
   * @covers Router\Exceptions\RouteException::__construct
   * @covers Router\Exceptions\SecurityException::__construct
   * @covers Router\Request::__construct
   * @covers Router\Request::execute
   */
  public function testFindRoutes(){

    AnnotationRegistry::registerAutoloadNamespace("Annotations", dirname(__DIR__).'/src');
    $handler = new RouteHandler();
    $handler->addControllersDirectory(__DIR__.'/Controllers');
    try{
      $handler->addControllersDirectory(__FILE__);
    }catch(RouteException $e){
      $this->assertInstanceOf(RouteException::class, $e);
    }
    $handler->findRoutes();

    $route = $handler->findRouteByName('route');

    $user = new Class implements \Router\Security\User{
      public function getUsername():string{ return 'username'; }
      public function isConnected():bool{ return true; }
      public function hasRole(string $role):bool{ return true; }
      public function hasRight(string $right):bool{ return true; }
    };
    $request = $route->call($user);
    $request->execute(array(1));

    $user2 = new Class implements \Router\Security\User{
      public function getUsername():string{ return 'username'; }
      public function isConnected():bool{ return false; }
      public function hasRole(string $role):bool{ return true; }
      public function hasRight(string $right):bool{ return true; }
    };
    try{
      $route->call($user2, array());
    }catch(SecurityException $e){
      $this->assertInstanceOf(SecurityException::class, $e);
    }

    $user3 = new Class implements \Router\Security\User{
      public function getUsername():string{ return 'username'; }
      public function isConnected():bool{ return true; }
      public function hasRole(string $role):bool{ return false; }
      public function hasRight(string $right):bool{ return true; }
    };
    try{
      $route->call($user3, array());
    }catch(SecurityException $e){
      $this->assertInstanceOf(SecurityException::class, $e);
    }
  }

  /**
   * @covers Router\RouteHandler::checkNotEmpty
   * @covers Router\RouteHandler::findRouteByName
   * @covers Router\Exceptions\RouteException::__construct
   */
  public function testEmpty(){
    $handler = new RouteHandler();
    try{
      $handler->findRouteByName('toto');
    }catch(RouteException $e){
      $this->assertEquals("No routes have been register", $e->getMessage());
    }
  }


  /**
   * @dataProvider providerForFindByNameOk
   * @covers Router\RouteHandler::addRoute
   * @covers Router\RouteHandler::checkNotEmpty
   * @covers Router\RouteHandler::findRouteByName
   */
  public function testFindByNameOk($routes, $name, $expected){
    $handler = new RouteHandler();
    foreach($routes as $route){
      $handler->addRoute($route);
    }
    $this->assertEquals($expected, $handler->findRouteByName($name));
  }


  /**
   * @dataProvider providerForFindByNameFail
   * @covers Router\RouteHandler::addRoute
   * @covers Router\RouteHandler::checkNotEmpty
   * @covers Router\RouteHandler::findRouteByName
   * @covers Router\Exceptions\RouteException::__construct
   */
  public function testFindByNameFail($routes, $name){
    $handler = new RouteHandler();
    foreach($routes as $route){
      $handler->addRoute($route);
    }
    try{
      $handler->findRouteByName($name);
    }catch(RouteException $e){
      $this->assertEquals("No route found", $e->getMessage());
    }
  }

  /**
   * @dataProvider providerForFindByPatternOk
   * @covers Router\RouteHandler::addRoute
   * @covers Router\RouteHandler::checkNotEmpty
   * @covers Router\RouteHandler::findRouteByPattern
   */
  public function testFindByPatternOk($routes, $pattern, $expected){
    $handler = new RouteHandler();
    foreach($routes as $route){
      $handler->addRoute($route);
    }
    $this->assertEquals($expected, $handler->findRouteByPattern($pattern));
  }


  /**
   * @dataProvider providerForFindByPatternFail
   * @covers Router\RouteHandler::addRoute
   * @covers Router\RouteHandler::checkNotEmpty
   * @covers Router\RouteHandler::findRouteByPattern
   * @covers Router\Exceptions\RouteException::__construct
   */
  public function testFindByPatternFail($routes, $pattern){
    $handler = new RouteHandler();
    foreach($routes as $route){
      $handler->addRoute($route);
    }
    try{
      $handler->findRouteByPattern($pattern);
    }catch(RouteException $e){
      $this->assertEquals("No route found", $e->getMessage());
    }
  }


  public function providerForFindByNameOk(){
    $route1 = new Route('Route 1', '/route1', 'Tests\RouteTest', 'call');
    $route2 = new Route('Route 2', '/route2', 'Tests\RouteTest', 'call');
    $route3 = new Route('Route 3', '/route3', 'Tests\RouteTest', 'call');
    return array(
      array(array($route1, $route2, $route3), 'Route 2', $route2)
    );
  }


  public function providerForFindByNameFail(){
    $route1 = new Route('Route 1', '/route1', 'Tests\RouteTest', 'call');
    $route2 = new Route('Route 2', '/route2', 'Tests\RouteTest', 'call');
    $route3 = new Route('Route 3', '/route3', 'Tests\RouteTest', 'call');
    return array(
      array(array($route1, $route2, $route3), 'Route', $route2)
    );
  }


  public function providerForFindByPatternOk(){
    $route1 = new Route('Route 1', '/toto/{$test}', 'Tests\RouteTest', 'call');
    $route2 = new Route('Route 2', '/user/{$user}', 'Tests\RouteTest', 'call');
    $route3 = new Route('Route 3', '/tata/{$tata}', 'Tests\RouteTest', 'call');
    return array(
      array(array($route1, $route2, $route3), '/user/3', $route2)
    );
  }


  public function providerForFindByPatternFail(){
    $route1 = new Route('Route 1', '/toto/{$test}', 'Tests\RouteTest', 'call');
    $route2 = new Route('Route 2', '/user/{$user}', 'Tests\RouteTest', 'call');
    $route3 = new Route('Route 3', '/tata/{$tata}', 'Tests\RouteTest', 'call');
    return array(
      array(array($route1, $route2, $route3), '/user', $route2)
    );
  }
}
