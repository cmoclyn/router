<?php

namespace Router\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Route{

  public $name;
  public $pattern;

}
