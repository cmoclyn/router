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
