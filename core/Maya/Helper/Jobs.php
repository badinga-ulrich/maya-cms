<?php

/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maya\Helper;

class Jobs extends \Lime\Helper {

    public function initialize(){

    }

    public function getJob() {
        $job = $this->app->storage->findOne('maya/jobs_queue', ['time'=>['$lt' => time()]]);
        return $job;
    }

    public function add($handle, $payload = null, $time = 0) {
        
        $job = [
            'handle' => $handle,
            'payload' => $payload,
            'time' => $time,
            '_created' => time()
        ];

        $this->app->storage->save('maya/jobs_queue', $job);

        return $job['_id'];
    }

    public function remove($id) {

        return $this->app->storage->remove('maya/jobs_queue', ['_id' => $id]);
    }

    public function work() {

        while ($job = $this->getJob()) {
            $this->execute($job);
        }
    }

    public function execute($job) {

        if (is_callable($job['handle'])) {
            $job['handle']($job['payload']);
        } elseif (function_exists($job['handle'])) {
            call_user_func($job['handle'], $job['payload']);
        } elseif (class_exists($job['handle'])) {
            $class = $job['handle'];
            $obj = new $class();

            if (method_exists($obj, 'handle')) {
                call_user_func_array([$obj, 'handle'], [$job['payload']]);
            }
        }

        $this->remove($job['_id']);
    }

    public function isRunnerActive() {

        foreach ((array)$this->app->storage->getKey('maya', 'jobs_queue_runners') as $pid) {
            if ($pid && posix_getsid($pid) !== false) return true;
        }

        return false;
    }

    public function stopRunner() {

        foreach ((array)$this->app->storage->getKey('maya', 'jobs_queue_runners') as $pid) {
    
            if ($pid && posix_getsid($pid) !== false && !posix_kill($pid, /* SIGTERM */ 15)) {
                
                if (MAYA_CLI) {
                    \CLI::writeln("Failed to kill process: {$pid} (".posix_strerror(posix_get_last_error()).')', false);
                }
            }
        }
        
        $this->app->storage->setKey('maya', 'jobs_queue_runners', []);
    }

    public function countJobs() {
        return $this->app->storage->count('maya/jobs_queue');
    }

    public function run($runnerIdle = 2) {

        $this->app->storage->rpush('maya', 'jobs_queue_runners', getmypid());

        while (true) {
            $this->work();
            sleep($runnerIdle);
        }
    }

}