<?php

namespace Router\Annotations;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Parameter{

  public $type;
  public $variable;

}
