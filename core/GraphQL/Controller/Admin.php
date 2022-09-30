<?php

namespace GraphQL\Controller;


class Admin extends \Maya\AuthController {


    public function playground() {

        return $this->render('graphql:views/playground.php');
    }

    public function graphiql() {

        $this->layout = false;

        return $this->render('graphql:views/graphiql.php');
    }

    public function query() {

        $query = $this->param('query', '{}');
        $variables = $this->param('variables', null);
    
        return $this->module('graphql')->query($query, $variables);
    }
}