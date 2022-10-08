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
define("SSE_MAX_EXECUTION_TIME",time()+6000);
class RestApi extends \LimeExtra\Controller {
    static $TTL = 0;
    static $ids = [];
    private function _events($chanel="events",$names=[]) {

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx: unbuffered responses suitable for Comet and HTTP streaming applications

        $callback = function () use($chanel,$names) {
            try {
                if(SSE_MAX_EXECUTION_TIME < time()) {
                    exit;
                }; 
                RestApi::$TTL = RestApi::$TTL == 0 ? (isset($_REQUEST["from"]) ? intval($_REQUEST["from"]) : time()) : RestApi::$TTL;
                $filter = [
                    'exp' => [
                        '$gte' => RestApi::$TTL
                    ],
                    '_id' => [
                        "\$nin" => RestApi::$ids
                    ],
                ];
                if(count($names)){
                    $filter["name"] = [
                        '$in' => $names
                    ];
                }
                $res = maya()->sse->find($chanel,[
                    'filter'=> $filter
                ])->toArray();
                // clean old events
                maya()->sse->remove($chanel,[
                    'exp' => [
                        '$gte' => time()
                    ]
                ]);
                $id = mt_rand(1, 1000);
                if($res && is_array($res)){
                    $res =  array_map(function($event) use($chanel){
                        $id = $event["_id"];
                        RestApi::$ids[] = $id;
                        if(isset($event["deleteOnRead"]) && $event["deleteOnRead"]){
                            maya()->sse->remove($chanel,[ '_id' => $event["_id"] ]);
                        }
                        return [
                            'id' => $id.'#'.RestApi::$TTL,
                            'event' => $event["name"],
                            'data' => $event["data"]
                        ];
                    },$res);
                }

            } catch (\Throwable $th) {
                // Stop if something happens to clear connection, browser will retry
                throw new StopSSEException();
            }
            RestApi::$TTL = time();
            return $res ? [
                "events" => $res
            ] : false;
            // return ['event' => 'ping', 'data' => 'ping data']; // Custom event temporarily: send ping event
            // return ['id' => uniqid(), 'data' => json_encode(compact('news'))]; // Custom event Id
        };
        (new SSE(new Event($callback, $chanel)))->start(5);
    }

    public function listen($chanel="events",$name="") {
        if(is_string($name) && !empty($name)){
            $this->_events($chanel,[$name]);
        }else{
            $this->_events($chanel,[]);
        }
    }
}
