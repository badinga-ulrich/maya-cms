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
            $user = maya()->module('maya')->getUser();
            $data = [
                'user' => $user ? [
                    "_id" => $user["_id"],
                    "name" => $user["name"],
                    "user" => $user["user"],
                    "group" => $user["group"],
                    "email" => $user["email"],
                ] : null,
                'name'=>$name,
                'time' => time(),
                'exp' => ($deleteOnRead ? strtotime('+1 years') /* expire in 1 year from now*/ : strtotime('+10 seconds')),
                'data' => $data,
                'deleteOnRead' => $deleteOnRead,
            ];
            if($chanel &&  $name){
                $app->sse->save($chanel, $data);
            }
            // clean old events
            maya()->sse->remove($chanel,[
                'exp' => [
                    '$lt' => time()
                ]
            ]);
        }
    ]);
    $triggers = new \ArrayObject([]);
    maya()->trigger('maya.webhook.events', [$triggers]);
    $names = $triggers->getArrayCopy();
    foreach ($names as  $name) {
        $app->on($name, function() use ($app, $name){
            $n = isset(func_get_args()[0]) && is_string(func_get_args()[0])?  func_get_args()[0] : (
                isset(func_get_args()[0]['name']) && is_string(func_get_args()[0]['name'])?  func_get_args()[0]['name'] : '' 
            );
            if($n && preg_match("#\{[^\}]+\}#",$name)
            ){
                $name = @preg_replace("#\{[^\}]+\}#",$n,$name);
            }else {
                $name = "";
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
// ADMIN
if (MAYA_ADMIN_CP) {
    include_once(__DIR__.'/admin.php');
}


// compact sse.memory
try {
    // try to repackage the database  into a minimal amount of disk space.
    maya()->sse->getCollection("events")->database->connection->exec("VACUUM;");
} catch (\Throwable $th) {
    //throw $th;
}