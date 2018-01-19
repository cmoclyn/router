<?php

namespace Router;

use Logguer\Log;
use Router\Security\User;


class Request{
  private $route;
  private $user;
  private $post = array();
  private $get = array();
  private $file = array();

  public function __construct(Route $route, User $user){
    $this->route = $route;
    $this->user = $user;
  }

  public function setPostData($data){
    $this->post = $data;
  }
  public function setGetData($data){
    $this->get = $data;
  }
  public function setFileData($data){
    $this->file = $data;
  }
  public function getPostData():array{
    return $this->post;
  }
  public function getGetData():array{
    return $this->get;
  }
  public function getFileData():array{
    return $this->file;
  }


  public function execute(array $params):void{
    $controller = $this->route->getController();
    call_user_func_array(array(new $controller, $this->route->getMethod()), $params);
  }

  public function __toString():string{
    $log = new Log();
    $log->setClass('Router\Request');
    $log->setMessage("User '{$this->user->getUsername()}', tried to access the route '{$this->route->getName()}'");
    return $log->__toString();
  }

}
