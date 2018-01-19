<?php

namespace Router\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Authorization{

  public $log;
  public $role;
  public $right;

}
