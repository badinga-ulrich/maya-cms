<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// storage
$app->service('sse', function() {
    return new MongoHybrid\Client('mongolite://'.(MAYA_STORAGE_FOLDER.'/data'), ['db' => 'sse.memory'], []);
});

// register global events
$app->on('maya.bootstrap', function() use($app){
    $app->module('maya')->extend([
        "publish" => function($name, $data, $chanel = "events", $deleteOnRead = false) use ($app){
            $time = time();
            $data = [
                'name'=>$name,
                'time' => $time,
                'exp' => $time + 10,
                'data' => $data,
                'deleteOnRead' => $deleteOnRead,
            ];
            $app->sse->save($chanel, $data);
        }
    ]);
    $triggers = new \ArrayObject([]);
    maya()->trigger('maya.webhook.events', [$triggers]);
    $names = $triggers->getArrayCopy();
    foreach ($names as  $name) {
        $app->on($name, function() use ($app, $name){
            if(preg_match("#\{[^\}]+\}#",$name) && is_string(func_get_args()[0])){
                $name = preg_replace("#\{[^\}]+\}#",func_get_args()[0],$name);
            }
            $app->module('maya')->publish($name, func_get_args());
        });
    }
});

// REST API
if (MAYA_API_REQUEST) {

    $app->on('maya.rest.init', function($routes) {
        $routes['sse'] = 'SSE\\Controller\\RestApi';
    });
    // allow access to public graphql query
    $app->on('maya.api.authenticate', function($data) use($app) {
        if ($data['user'] || $data['resource'] != 'sse') return;

        if ($app->retrieve('config/sse/publicAccess', false)) {
            $data['authenticated'] = true;
            $data['user'] = ['_id' => null, 'group' => 'public'];
        }
    });
}
