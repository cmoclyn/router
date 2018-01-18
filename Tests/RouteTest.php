<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

use Router\Route;
use Router\UserInterface;
use Router\RoleInterface;

class RouteTest extends TestCase{

  /**
   * @dataProvider providerForTestPattern
   * @covers Router\Route::__construct
   * @covers Router\Route::clearPattern
   * @covers Router\Route::getPattern
   * @covers Router\Route::getName
   */
  public function testPattern($pattern, $expected){
    $route = new Route('user', $pattern, 'Controllers\User', 'user');
    $this->assertEquals($expected, $route->getPattern());
    $this->assertEquals('user', $route->getName());
  }



  public function providerForTestPattern(){
    return array(
      array('/user/{$user}', '/^\/user\/(.*)\/?$/'),
      array('/user/{$userSource}/{$userCible}', '/^\/user\/(.*)\/(.*)\/?$/', array('int', 'int')),
    );
  }


}
