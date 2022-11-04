<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SSE\Controller;

require_once(__DIR__.'/../lib/Event.php');
require_once(__DIR__.'/../lib/SSE.php');
require_once(__DIR__.'/../lib/StopSSEException.php');

use Hhxsv5\SSE\Event;
use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\StopSSEException;
// define("SSE_MAX_EXECUTION_TIME",time()+(3600 * 5)); // max 5h
define("SSE_MAX_EXECUTION_TIME",time()+(10)); // max 10s
class RestApi extends \LimeExtra\Controller {
    static $TTL = 0;
    static $ids = [];
    /**
     * @param Array<String>|String $names
     * @param String $channel
     * 
     * @return void
     */
    private function _events( $names,$channel="events") {
        /**
         * @var Array<String>
         */
        if(empty($names)){
            $names = [];
        }
        $namesArray = is_array($names) ? $names :  explode(",","$names");

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

        $callback = function () use($channel,$namesArray) {
            $res = null;
            try {
                if(SSE_MAX_EXECUTION_TIME < time()) {
                    exit;
                }; 
                RestApi::$TTL = RestApi::$TTL == 0 ? (isset($_REQUEST["from"]) ? intval($_REQUEST["from"]) : time()) : RestApi::$TTL;

                // clean old events
                maya()->sse->remove($channel,[
                    'exp' => [
                        '$lt' => RestApi::$TTL
                    ]
                ]);
                // purge old ids
                foreach (RestApi::$ids as $id => $ttl) {
                    if($ttl < RestApi::$TTL){
                        unset($id);
                    }
                }
                $filter = [
                    'exp' => [
                        '$gte' => RestApi::$TTL
                    ],
                    '_id' => [
                        '$nin' => array_keys(RestApi::$ids)
                    ],
                    '$or' => array_map(function($name){
                        return [
                            "name" => [
                                '$like' => strtolower(preg_replace("/[^a-zA-Z0-9-:_\.\*%]/","",$name))
                            ]
                        ];
                    },$namesArray)
                ];
                if(count(RestApi::$ids) == 0){
                    unset($filter['_id']);
                }
                if(count($namesArray) == 0){
                    unset($filter['$or']);
                    maya()->stop(["error"=>"No Event selected"], 403);

                } else if(count($namesArray) == 1){
                    $filter["name"] = $filter['$or'][0]["name"];
                    unset($filter['$or']);
                }else{
                    // unset($filter['$or']);
                    // unset($filter['name']);
                }
                // var_dump($filter);
                $channel = strtolower($channel);
                $res = maya()->sse->find($channel,[
                    'filter'=> $filter
                ])->toArray();
                
                if($res && is_array($res)){
                    $res =  array_map(function($event) use($channel){
                        $id = (isset($event["user"]['user'],$event["user"]['group']) ? ($event["user"]['user'].'@'.$event["user"]['group'].'#') : "").$event["_id"];
                        if(isset($event["deleteOnRead"]) && $event["deleteOnRead"]){
                            maya()->sse->remove($channel,[ '_id' => $event["_id"] ]);
                        }
                        RestApi::$ids[$event["_id"]] = $event["exp"];
                        return [
                            'id' => $id,
                            'event' => $event["name"],
                            'data' => $event["data"]
                        ];
                    }, array_filter($res, function($el){
                        return array_search($el["_id"], array_keys(RestApi::$ids)) === false;
                    }));
                }

            } catch (\Throwable $th) {
                // Stop if something happens to clear connection, browser will retry
                throw new StopSSEException();
            }finally{
                RestApi::$TTL = time();
                return $res ? [
                    "events" => $res
                ] : false;
            }
        };
        (new SSE(new Event($callback, $channel)))->start(5);
    }

    public function channel($channel,$name) {
        $this->_events($name,$channel);
    }
    public function publish($channel,$name) {
        if($channel != "events"){
            maya()->module('maya')->publish($name, isset($_REQUEST["data"]) && is_array($_REQUEST["data"]) || is_scalar($_REQUEST["data"]) ? $_REQUEST["data"] : null,$channel, isset($_REQUEST["volatile"]) && $_REQUEST["volatile"] ? true : false);
            maya()->stop(204);
        }
        maya()->stop(400,"Invalid chanel event");
    }
}
