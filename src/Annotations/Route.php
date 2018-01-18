<?php

namespace Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Route{

  public $name;
  public $pattern;

}
