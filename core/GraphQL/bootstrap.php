<?php

spl_autoload_register(function($class){
    $class_path = __DIR__.'/lib/'.str_replace('\\', '/', $class).'.php';
    if(file_exists($class_path)) include_once($class_path);
});


$this->module('graphql')->extend([

    'query' => function($query = '{}', $variables = null) {
        return \GraphQL\Query::process($query, $variables);
    }
]);

// ADMIN
if (MAYA_ADMIN && !MAYA_API_REQUEST) {
    include_once(__DIR__.'/admin.php');
}

// REST
if (MAYA_API_REQUEST) {

    $app->on('maya.rest.init', function($routes) use($app) {

        $routes['graphql'] = 'GraphQL\\Controller\\RestApi';
    });

    // allow access to public graphql query
    $app->on('maya.api.authenticate', function($data) {
        
        if ($data['user'] || $data['resource'] != 'graphql') return;

        if ($this->retrieve('config/graphql/publicAccess', false)) {
            $data['authenticated'] = true;
            $data['user'] = ['_id' => null, 'group' => 'public'];
        }
    });
}
