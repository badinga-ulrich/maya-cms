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

use Firebase\JWT\JWT;

class Csrf extends \Lime\Helper {

    public function initialize(){


    }

    public function generateToken($key, $expire = null) {

        $payload = ['csrf' => $key];

        if ($expire && is_numeric($expire)) {
            $payload['exp'] = $expire;
        }

        $token = JWT::encode($payload, $this->app['sec-key']);

        $this->app->helper('session')->write("maya.csrf.token.{$key}", $token);

        return $token;
    }

    public function token($key, $generate = false, $expire = null) {

        $token = $this->app->helper('session')->read("maya.csrf.token.{$key}", null);

        if (!$token || $generate) {
            $token = $this->generateToken($key, $expire);
        }

        return $token;
    }

    public function isValid($key, $token, $checkpayload = false) {

        if (!$token) {
            return false;
        }

        if ($checkpayload) {
            try {
                $payload = JWT::decode($token, $this->app['sec-key'], ['HS256']);
                return isset($payload->csrf) && $payload->csrf == $key;
            } catch(\Exception $e) {
                return false;
            }
        }

        $stoken = $this->app->helper('session')->read("maya.csrf.token.{$key}", null);

        if ($token != $stoken) {
            return false;
        }

        try {
            $token = JWT::decode($token, $this->app['sec-key'], ['HS256']);
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }
}
