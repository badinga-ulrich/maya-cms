<?php

use GraphQL\Type\Definition\Type;
use GraphQL\Types\JsonType;

$queries['fields']['region'] = [
    
    'type' => Type::string(),
    'args' => [
        'name' => Type::nonNull(Type::string()),
        'params' => JsonType::instance(),
        'render' => [
            'type' => Type::boolean(),
            'defaultValue' => false
        ]
    ],
    'resolve' => function ($root, $args) use($app) {

        $name = $args['name'];

        if (!$app->module('regions')->exists($name)) {
            return '{"error": "Region not found"}';
        }

        if ($app->module('maya')->getUser()) {
            if (!$app->module('regions')->hasaccess($name, 'form')) {
                return '{"error": "Unauthorized"}';
            }
        }

        $params = [];

        if (isset($args['params']) && $args['params']) {
            $params = $args['params'];
        }

        if ($args['render']) {
            return maya('regions')->render($name, $params);
        } else {
            return json_encode(maya('regions')->region($name));
        }

    }
];
