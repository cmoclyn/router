<?php

namespace Router\Security;

interface User{
  public function getUsername():string;
  public function isConnected():bool;
  public function hasRole(string $role):bool;
  public function hasRight(string $right):bool;
}
