<?php
//
// namespace Tests;
//
// use PHPUnit\Framework\TestCase;
//
// use Router\RouteHandler;
// use Router\Request;
// use Router\Route;
// use Router\Security\User;
//
// class RequestTest extends TestCase{
//   private $routeHandler;
//
//   public function setUp(){
//     $this->routeHandler = new RouteHandler();
//
//     $this->routeHandler->addRoute(new Route('Route 1', '/toto/{$test}', 'Tests\RouteTest', 'call'));
//     $this->routeHandler->addRoute(new Route('Route 2', '/user/{$user}', 'Tests\RouteTest', 'call'));
//     $this->routeHandler->addRoute(new Route('Route 3', '/tata/{$tata}', 'Tests\RouteTest', 'call'));
//   }
//
//   /**
//    * @covers Router\Request::__construct
//    * @covers Router\Request::setPostData
//    * @covers Router\Request::setGetData
//    * @covers Router\Request::setFileData
//    * @covers Router\Request::getPostData
//    * @covers Router\Request::getGetData
//    * @covers Router\Request::getFileData
//    * @covers Router\Request::__toString
//    */
//   public function testOk(){
//     $user = new Class implements User{
//         public function getUsername():string{ return 'toto'; }
//         public function isConnected():bool{ return true; }
//         public function hasRole(string $role):bool{ }
//         public function hasRight(string $right):bool{ }
//       }
//     };
//     $request = new Request($this->routeHandler->findRouteByName('Route 1'), $user);
//     $request->setPostData(array('post'));
//     $request->setGetData(array('get'));
//     $request->setFileData(array('file'));
//     $this->assertEquals($request->getPostData(), array('post'));
//     $this->assertEquals($request->getGetData(), array('get'));
//     $this->assertEquals($request->getFileData(), array('file'));
//     $this->assertInstanceOf(Request::class, $request);
//     $this->assertEquals(1, preg_match('/User \'toto\', tried to access the route \'Route 1\'/', $request->__toString()));
//   }
//
// }
