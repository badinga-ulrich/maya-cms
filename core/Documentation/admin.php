<?php


$app->on('admin.init', function() {

    if (!$this->module('maya')->isSuperAdmin()) {

        $this->bind('/documentation/*', function() {
            return $this('admin')->denyRequest();
        });

        return;
    }

    // bind admin routes /singleton/*
    $this->bindClass('Documentation\\Controller\\Admin', 'documentation');
    if ($this->module('maya')->hasaccess('maya', 'rest')) {
        $active = strpos($this['route'], '/documentation/admin') === 0;
        $this->on('maya.menu.system', function() use ($active){
            $this->renderView("documentation:views/partials/menu.php",[
               "active" => $active 
            ]);
        });
        
        if ($active) {
            $this->helper('admin')->favicon = 'documentation:icon.svg';
        } 
    }
});