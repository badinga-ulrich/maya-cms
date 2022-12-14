<?php

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Types\JsonType;
use GraphQL\Types\FieldType;


$singletons = maya('singletons')->singletons();

foreach ($singletons as $name => &$meta) {

    // $_name = $name.'Singleton';
    $_name = "_".$name;

    $queries['fields'][$_name] = [

        'type' => new ObjectType([
            'name'   => $_name,
            'fields' => function() use($meta, $app, $_name) {

                $fields = array_merge([
                    '_id' => Type::string(),
                    '_by' => Type::string(),
                    '_mby' => Type::string(),
                    '_created' => Type::int(),
                    '_modified' =>Type::int()
                ], FieldType::buildFieldsDefinitions($meta));

                $app->trigger("graphql.{$_name}.fields", [&$fields]);

                return $fields;
            }
        ]),

        'args' => [
            'lang'  => Type::string(),
            'populate'   => ['type' => Type::int(), 'defaultValue' => 0],
        ],

        'resolve' => function ($root, $args) use($app, $name) {

            $singleton = $app->module('singletons')->singleton($name);
            $user = $app->module('maya')->getUser();

            if ($user) {

                if (!$app->module('singletons')->hasaccess($singleton['name'], 'data')) {
                    $app->stop(['error'=> 'Unauthorized'], 401);
                }
            }

            $options  = [];

            if (isset($args['lang']) && $args['lang']) {
                $options['lang'] = $args['lang'];
            }

            if (isset($args['populate']) && $args['populate']) {
                $options['populate'] = $args['populate'];
            }

            if ($user) {
                $options['user'] = $user;
            }

            return maya('singletons')->getData($name, $options);
        }
    ];
}
