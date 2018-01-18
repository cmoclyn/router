# Router for PHP 7

## Documentation
---------------

[![Build Status](https://travis-ci.org/cmoclyn/router.svg?branch=master-7)](https://travis-ci.org/cmoclyn/router)
[![codecov](https://codecov.io/gh/cmoclyn/router/branch/master-7/graph/badge.svg)](https://codecov.io/gh/cmoclyn/router)


### .htaccess example
```htaccess
RewriteEngine On

RewriteCond %{REQUEST_URI} "^.*\.css" # If CSS
RewriteRule "^(.*)$" "web/css/$1" [END] # CSS folder

RewriteCond %{HTTP_HOST} "^localhost" # If localhost
RewriteRule "^(.*)$" "web/web_dev.php" [END] # Dev

RewriteRule "^(.*)$" "web/web.php" [END] # Prod
```

### Use the router

To use the router, you just have to create a `.htaccess` like the above's one, at the root project, and create a `web` folder in which you will put `web.php`, `web_dev.php` and all the resources you need.

To find all the route, you have to do :

```php
<?php
$routeHandler = new RouteHandler();
$routeHandler->addControllersDirectory(__DIR__.'/src'); // Set where the controller's directory is (can set many directories)
$routeHandler->findRoutes();
```

After that, you can find a route with :

```php
<?php
$route = $routeHandler->findRouteByName($name);
// Or
$route = $routeHandler->findRouteByPattern($pattern);
```

And execute it with :

```php
<?php
// $user is the current user, log in or not ($user->isConnected() must return false in that case)
// $args is the TODO 
$route->call($user, $args);
```

### Create Route

To create some routes, you have to declare where are the annotations you want to use :
```php
<?php
use Doctrine\Common\Annotations\AnnotationRegistry;

// If the annotations are in an "Annotations" directory which one is in the current directory
AnnotationRegistry::registerAutoloadNamespace("Annotations", __DIR__);
```

In the file you want use these annotations, you have to put at the beggining of the file :

```php
<?php
use Annotations\{Route, Parameter, Authorization};
```

The differents kind of annotations used for the route are :
- name and pattern
- parameter
- authorization

```php
<?php

/**
 * @Route(name="changeRoute", pattern="/changeRoute/{$id}")
 * @Parameter(type="int", variable="$id")
 * @Authorization(log="true")
 * @Authorization(role={"admin"})
 * @Authorization(right={"change_route", "show_route"})
 */
public function changeRoute($id){

}
```

> Note
>
> This code above means that we have a Route called "changeRoute", that match on "/changeRoute/(.\*)" pattern.
>
> It also has a parameter of type int.
>
> The user has to be connected, admin, and all the rights given
