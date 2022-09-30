<?php

/**
 * register routes
 */

$app->bind('/', function() use($app) {
  $user = $app->module('maya')->getUser();

  if (!$user || isset($_REQUEST["view_public"])) {
      if ($file = $app->path('#public:index.htm')) {
        include($file);
        exit;
      }else if ($file = $app->path('#public:index.html')) {
        include($file);
        exit;
    }else if ($file = $app->path('#public:index.php')) {
        include($file);
        exit;
      }else if(!isset($_REQUEST["view_public"])){
          $app->reroute('/auth/login');
          $app->stop();
      }
      return false;
  }else{
    // display dashboard
      if ($app['maya.start'] && $app->module('maya')->getUser()) {
          $app->reroute($app['maya.start']);
      }
  
      return $app->invoke('Maya\\Controller\\Base', 'dashboard');
  }
});
