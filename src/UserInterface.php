<?php

namespace Router;

interface UserInterface{

  public function getUsername():string;
  public function getRole():RoleInterface;
}
