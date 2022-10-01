<?php


$app->on('admin.init', function() {

    if (!$this->module('maya')->isSuperAdmin()) {

        $this->bind('/graphql/*', function() {
            return $this('admin')->denyRequest();
        });

        return;
    }

    // bind admin routes /singleton/*
    $this->bindClass('GraphQL\\Controller\\Admin', 'graphql');
    if ($this->module('maya')->hasaccess('maya', 'rest')) {
        $this->on('maya.menu.system', function() {
            $this->renderView("graphql:views/partials/menu.php");
        });
        $active = strpos($this['route'], '/graphql/playground') === 0;
        
        // add to modules menu
        $this->helper('admin')->addMenuItem('modules', [
            'label' => 'GraphQL',
            'icon'  => 'graphql:icon.svg',
            'route' => '/graphql/playground',
            'active' => $active
        ]);
        
        if ($active) {
            $this->helper('admin')->favicon = 'graphql:icon.svg';
        } 
    }
});