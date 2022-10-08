<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$this->on('before', function() {

    $routes = new \ArrayObject([]);

    /*
        $routes['{:resource}'] = string (classname) | callable
    */
    $this->trigger('maya.rest.init', [$routes])->bind('/api/*', function($params) use($routes) {

        $this->module('maya')->setUser(false, false);

        $route = $this['route'];
        $path  = $params[':splat'][0];

        if (!$path) {
            return false;
        }

        $token = $this->param('token', $this->request->server['HTTP_MAYA_TOKEN'] ?? $this->helper('utils')->getBearerToken());

        // api key check
        $allowed = false;

        // is account token?
        if ($token && preg_match('/account-/', $token)) {
            
            $account = $this->storage->findOne('maya/accounts', ['api_key' => $token]);

            if ($account) {
                $allowed = true;
                $this->module('maya')->setUser($account, false);
            }

        // is jwt token?
        } elseif ($token && preg_match('/^[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.[A-Za-z0-9-_]*$/', $token)) {

            // todo

        // default
        } elseif ($token) {

            $apikeys = $this->module('maya')->loadApiKeys();

            // check master for master key
            $allowed = (isset($apikeys['master']) && trim($apikeys['master']) && $apikeys['master'] == $token);

            if (!$allowed && count($apikeys['special'])) {

                foreach ($apikeys['special'] as &$apikey) {

                    if ($apikey['token'] == $token) {

                        $rules = trim($apikey['rules']);

                        if ($rules == '*') {
                            $allowed = true;
                            break;
                        }

                        $rules = explode("\n", $rules);

                        foreach ($rules as &$rule) {

                            $rule = trim($rule);
                            if (!$rule) continue;

                            if ($rule == '*' || preg_match("#{$rule}#", MAYA_ADMIN_ROUTE)) {
                                $allowed = true;
                                break;
                            }
                        }

                        break;
                    }
                }
            }

        }

        $parts    = explode('/', $path, 2);
        $resource = $parts[0];
        $params   = isset($parts[1]) ? explode('/', $parts[1]) : [];

        // trigger authenticate event
        if (!$allowed) {

            $data = new ArrayObject([
                'token' => $token,
                'authenticated' => false,
                'resource' => $resource,
                'query' => ['path' => $path, 'parts' => $parts, 'params' => $params],
                'user' => null,
            ]);

            $this->trigger('maya.api.authenticate', [$data]);

            $allowed = $data['authenticated'];

            if ($data['user']) {
                $this->module('maya')->setUser($data['user'], false);
            }
        }

        $output = false;
        $user   = $this->module('maya')->getUser();

        if (strpos($path, '../') !== false) {
            // normalize path
            $path = implode('/', array_filter(explode('/', $path), function($s) { return trim($s, '.'); }));
        }

        if ($resource == 'public' && $resourcefile = $this->path("#config:api/{$path}.php")) {

            $output = include($resourcefile);

        } elseif ($allowed && $resourcefile = $this->path("#config:api/{$path}.php")) {

            $output = include($resourcefile);

        } elseif ($allowed && isset($routes[$resource])) {

            try {

                // invoke class
                if (is_string($routes[$resource])) {

                    $action = count($params) ? array_shift($params) : 'index';
                    $output = $this->invoke($routes[$resource], $action, $params);

                } elseif (is_callable($routes[$resource])) {
                    $output = call_user_func_array($routes[$resource], $params);
                }

            } catch(Exception $e) {

                $output = ['error' => true];

                $this->response->status = 406;
                $this->trigger('maya.api.erroronrequest', [$route, $e->getMessage()]);

                if ($this['debug']) {
                    $output['message'] = $e->getMessage();
                } else {
                    $output['message'] = 'Oooops, something went wrong.';
                }
            }
        }

        if ($output === false && $resource == 'public') {
            $this->response->mime = 'json';
            $this->stop(404);
        }

        if ($output === false && !$allowed) {
            $this->response->mime = 'json';
            $this->stop(401);
        }

        if (is_object($output) || is_array($output)) {
            $this->response->mime = 'json';
        }

        return $output;
    });

});
