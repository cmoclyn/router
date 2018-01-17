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
   * @covers Router\Route::getArgs
   */
  public function testPattern($pattern, $expected, $args){
    $route = new Route('user', $pattern, 'Controllers\User', 'user', $args);
    $this->assertEquals($expected, $route->getPattern());
    $this->assertEquals('user', $route->getName());
    $this->assertEquals($args, $route->getArgs());
  }


  /**
   * @covers Router\Route::__construct
   * @covers Router\Route::setRights
   * @covers Router\Route::canUser
   */
  public function testRightsNotStrict(){
    $route = new Route('user', 'pattern', 'Controllers\User', 'user');
    $route->setRights(array('right 1', 'right 2', 'right 3'));

    $user = new Class implements UserInterface{
        public function getUsername():string{ return 'toto'; }
        public function getRole():RoleInterface{ return new Class implements RoleInterface{
          public function getRights():array{
            return array('right 2');
          }
        };
      }
    };
    $this->assertTrue($route->canUser($user));
  }

  /**
   * @covers Router\Route::__construct
   * @covers Router\Route::setRights
   * @covers Router\Route::canUser
   */
  public function testRightsStrict(){
    $route = new Route('user', 'pattern', 'Controllers\User', 'user');
    $route->setRights(array('right 1', 'right 2', 'right 3'), true);

    $user = new Class implements UserInterface{
        public function getUsername():string{ return 'toto'; }
        public function getRole():RoleInterface{ return new Class implements RoleInterface{
          public function getRights():array{
            return array('right 2');
          }
        };
      }
    };

    $user2 = new Class implements UserInterface{
        public function getUsername():string{ return 'toto'; }
        public function getRole():RoleInterface{ return new Class implements RoleInterface{
          public function getRights():array{
            return array('right 2', 'right 3', 'right 1');
          }
        };
      }
    };
    $this->assertTrue($route->canUser($user2));
  }

  /**
   * @dataProvider providerForTestCall
   * @covers Router\Route::call
   */
  public function testcall($route, $expected, $args){
    $this->assertEquals($expected, $route->call($args));
  }

  public function call($args = array()){
    return implode(', ', $args);
  }

  public function providerForTestPattern(){
    return array(
      array('/user/{$user}', '/^\/user\/(.*)\/?$/', array('int')),
      array('/user/{$userSource}/{$userCible}', '/^\/user\/(.*)\/(.*)\/?$/', array('int', 'int')),
    );
  }

  public function providerForTestCall(){
    return array(
      array(new Route('Route 1', '/route1', 'Tests\RouteTest', 'call'), '', array()),
      array(new Route('Route 2', '/route2', 'Tests\RouteTest', 'call'), 'Hello, World', array(array('Hello', 'World'))),
    );
  }
}
