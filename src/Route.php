<?php

namespace Router;

class Route{
  private $name;
  private $pattern;
  private $controller;
  private $method;
  private $args;
  private $rights;
  private $strict;

  public function __construct(string $name, string $pattern, string $controller, string $method, array $args = array()){
    $this->name       = $name;
    $this->pattern    = $this->clearPattern($pattern);
    $this->controller = $controller;
    $this->method     = $method;
    $this->args       = $args;
  }


  public function setRights(array $rights, bool $strict = false){
    $this->rights = $rights;
    $this->strict = $strict;
  }

  public function canUser(UserInterface $user):bool{
    $bool = true;
    foreach($this->rights as $right){
      if(in_array($right, $user->getRole()->getRights())){
        if(!$this->strict){
          return true;
        }
        $bool = true && $bool;
      }else{
        $bool = false;
      }
    }
    return $bool;
  }

  /**
   * [clearPattern description]
   * @param  [type] $pattern [description]
   * @return [type]          [description]
   */
  private function clearPattern($pattern){
    $str = trim($pattern, '/');
    $str = preg_replace('/{.*}/U', '(.*)', $str);
    $str = str_replace('/', '\/', $str);
    return "/^\/$str\/?$/";
  }


  public function call(array $params){
    return call_user_func_array(array(new $this->controller, $this->method), $params);
  }

  /**
   * Get the value of Name
   *
   * @return string
   */
  public function getName():string{
    return $this->name;
  }

  /**
   * Get the value of Pattern
   *
   * @return string
   */
  public function getPattern():string{
    return $this->pattern;
  }

  /**
   * Get the value of Args
   *
   * @return array
   */
  public function getArgs():array{
    return $this->args;
  }

}
