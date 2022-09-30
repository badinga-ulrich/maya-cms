<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('MAYA_ADMIN', 1);

// set default timezone
date_default_timezone_set('UTC');

// handle php webserver
if (PHP_SAPI == 'cli-server' && is_file(__DIR__.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// bootstrap maya
require(__DIR__.'/bootstrap.php');

# admin route
if (MAYA_ADMIN && !defined('MAYA_ADMIN_ROUTE')) {

    $route = preg_replace('#'.preg_quote(MAYA_BASE_URL, '#').'#', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);

    if ($route == '') {
        $route = '/';
    }

    define('MAYA_ADMIN_ROUTE', $route);
}

if (MAYA_API_REQUEST) {

    $_cors = $maya->retrieve('config/cors', []);

    header('Access-Control-Allow-Origin: '      .($_cors['allowedOrigins'] ?? '*'));
    header('Access-Control-Allow-Credentials: ' .($_cors['allowCredentials'] ?? 'true'));
    header('Access-Control-Max-Age: '           .($_cors['maxAge'] ?? '1000'));
    header('Access-Control-Allow-Headers: '     .($_cors['allowedHeaders'] ?? 'X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding, Maya-Token'));
    header('Access-Control-Allow-Methods: '     .($_cors['allowedMethods'] ?? 'PUT, POST, GET, OPTIONS, DELETE'));
    header('Access-Control-Expose-Headers: '    .($_cors['exposedHeaders'] ?? 'true'));

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }
}


// run backend
$maya->set('route', MAYA_ADMIN_ROUTE)->trigger('admin.init')->run();
