<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// add url only if the request start with /api/*
if(MAYA_API_REQUEST){
    // Test page (REST API)
    $app->bind('/api/sample', function() {
        $this->response->mime = 'json';
        return json_encode([
            'status' => 200, 
            'message' => $this('i18n')->get('Sample Addon works!')
        ]);
    });
    // send SSE Event
    $app->bind('/api/sample/:from/:to', function($params) {
        $f = [$params["from"], $params["to"]];
        sort($f);
        $this->module("maya")->publish(
            $params["from"]."::".$params["to"], 
            $_REQUEST["message"],
            "messages",
            true
        );
        return json_encode([
            'status' => 200, 
            'message' => $this('i18n')->get('Sample Addon works!')
        ]);
    });
}