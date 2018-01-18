<?php

namespace Router;

use Annotations\{Parameter, Authorization};
use Router\Security\User;
use Exceptions\SecurityException;

/**
 * Class Route
 */
class Route{
  private $name;
  private $pattern;
  private $controller;
  private $method;
  private $args = array();
  private $authorization;
  private $log;

  /**
   * Constructor of the class Route
   *
   * @param string $name       Name of the route (@route(name="$name"))
   * @param string $pattern    Pattern of the route (@route(pattern="$pattern"))
   * @param string $controller Controller of the route (set automatically by the RouteHandler)
   * @param string $method     Method of the route (set automatically by the RouteHandler)
   */
  public function __construct(string $name, string $pattern, string $controller, string $method){
    $this->name       = $name;
    $this->pattern    = $this->clearPattern($pattern);
    $this->controller = $controller;
    $this->method     = $method;
  }

  /**
   * Add a parameter to the Route
   *
   * @param Parameter $parameter Parameter to add
   */
  public function addParameter(Parameter $parameter){
    $this->args[] = $parameter;
  }

  /**
   * Add an authorization to access the Route
   *
   * There is 3 types of Authorization :
   *   - log (true: the user must be log in; false: the user must be log out)
   *   - roles (the user must have one of the given roles)
   *   - rights (the user must have all of the given rights)
   *
   * @param Authorization $authorization Authorization to add
   */
  public function addAuthorization(Authorization $authorization){
    if(!is_null($authorization->log)){
      $this->log = $authorization->log;
    }
    if(!is_null($authorization->role)){
      $this->authorization['roles'] = $authorization->role;
    }
    if(!is_null($authorization->right)){
      $this->authorization['rights'] = $authorization->right;
    }
  }

  /**
   * Check if the given User has all the required Authorizations for this Route
   *
   * @param  User $user User to check
   * @return bool       True : the route can be call for the given User; false : it can't
   */
  private function checkAuthorization(User $user):bool{
    if(!is_null($this->log)){
      // If the route required the user to be connected and he isn't
      // Or if the route required the user to be disconnected and he is
      if(($this->log && !$user->isConnected()) || (!$this->log && $user->isConnected())){
        return false;
      }
    }
    if(!empty($this->authorization['roles'])){
      $bool = false;
      // If the user don't have one of the roles required
      foreach($this->authorization['roles'] as $role){
        if($user->hasRole($role)){
          $bool = true;
        }
      }
      if(!$bool){
        return false;
      }
    }
    if(!is_null($this->authorization['rights'])){
      foreach($this->authorization['rights'] as $right){
        if(!$user->hasRight($right)){ // If the user don't have all of the rights
          return false;
        }
      }
    }
    return true;
  }

  /**
   * Modify the given pattern to be a true pattern
   *
   * @param  string $pattern A human readable pattern ("/pattern/{$var}")
   * @return string          A true pattern ("/\/pattern\/(.*)/")
   */
  private function clearPattern(string $pattern):string{
    $str = trim($pattern, '/');
    $str = preg_replace('/{.*}/U', '(.*)', $str);
    $str = str_replace('/', '\/', $str);
    return "/^\/$str\/?$/";
  }

  /**
   * Execute the Route for the given User
   *
   * @param  User   $user   User to execute the Route with
   * @param  array  $params The parameters to pass to method
   */
  public function call(User $user, array $params):void{
    if(!$this->checkAuthorization($user)){
      throw new SecurityException("The User '{$user->getUsername()}' doesn`t have the rights to execute the Route '{$this->name}'");
    }
    // TODO Return a request ready to use
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
