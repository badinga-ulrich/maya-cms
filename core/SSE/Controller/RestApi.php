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
define("SSE_MAX_EXECUTION_TIME",time()+(6000 * 5)); // max 5h
class RestApi extends \LimeExtra\Controller {
    static $TTL = 0;
    static $ids = [];
    /**
     * @param Array<String>|String $names
     * @param String $chanel
     * 
     * @return void
     */
    private function _events( $names,$chanel="events") {
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

        $callback = function () use($chanel,$namesArray) {
            $res = null;
            try {
                if(SSE_MAX_EXECUTION_TIME < time()) {
                    exit;
                }; 
                RestApi::$TTL = RestApi::$TTL == 0 ? (isset($_REQUEST["from"]) ? intval($_REQUEST["from"]) : time()) : RestApi::$TTL;

                // clean old events
                maya()->sse->remove($chanel,[
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
                                '$like' => $name
                            ]
                        ];
                    },$namesArray)
                ];
                if(count($namesArray) == 0){
                    unset($filter['$or']);
                    maya()->stop(["error"=>"No Event selected"], 403);

                } else if(count($namesArray) == 1){
                    $filter["name"] = $filter['$or'][0]["name"];
                    unset($filter['$or']);
                }
                unset($filter['$or']);
                unset($filter['name']);

                $res = maya()->sse->find($chanel,[
                    'filter'=> $filter
                ])->toArray();
                
                if($res && is_array($res)){
                    $res =  array_map(function($event) use($chanel){
                        $id = (isset($event["user"]['user'],$event["user"]['group']) ? ($event["user"]['user'].'@'.$event["user"]['group'].'#') : "").$event["_id"];
                        if(isset($event["deleteOnRead"]) && $event["deleteOnRead"]){
                            maya()->sse->remove($chanel,[ '_id' => $event["_id"] ]);
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
        (new SSE(new Event($callback, $chanel)))->start(5);
    }

    public function chanel($chanel,$name) {
        $this->_events($name,$chanel);
    }
}
