<?php

namespace Tests\Controllers;

use \Router\Annotations\{Route, Parameter, Authorization};

class ControllerExample{

  /**
   * @Route(name="index", pattern="/")
   */
  public function index(){

  }

  /**
   * @Route(name="route", pattern="/route/{$route}")
   * @Parameter(type="Route", variable="$route")
   * @Authorization(log="true", role={"admin"}, right={"see_route"})
   */
  public function route($route){

  }
}
