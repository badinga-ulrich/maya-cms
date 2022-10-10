<?php

spl_autoload_register(function($class){
  if(preg_match("#^HTMLEditor\\\\#",$class)){
    $class = str_ireplace("htmleditor","",$class);
    $class_path = preg_replace('#[/\\\\]+#', '/',__DIR__.'/'.$class.'.php');
    if(file_exists($class_path)) include_once($class_path);
  }
});

$app->on('admin.init', function() {
  if (!$this->module('maya')->isSuperAdmin()) {
    $this->bind('/editor/*', function() {
      return $this('admin')->denyRequest();
    });
    return;
  }
  /**
   * register routes
   */
  $this->bind('/editor', function() {
    return $this->invoke('HTMLEditor\\Controller\\Admin', 'editor');
  });
  $this->bindClass('HTMLEditor\\Controller\\Admin', 'editor');

  $active = strpos($this['route'], '/editor') === 0;
  // add to modules menu
  $this->helper('admin')->addMenuItem('modules', [
    'label' => 'Editor',
    'icon'  => 'htmleditor:icon.svg',
    'route' => '/editor',
    'active' => $active
  ]);

  if ($active) {
      $this->helper('admin')->favicon = 'htmleditor:icon.svg';
  }
  /*
  * add menu entry if the user has access to group stuff
  */
  $this->on('maya.menu.system', function() use ($active){
      $this->renderView("htmleditor:views/partials/menu.php",[
        "active" => $active 
    ]);
  });
  /*
  * add menu entry if the user has access to group stuff
  */
  $this->on('maya.groups.settings.generic', function() use ($active){
      $this->renderView("htmleditor:views/partials/groups.php",[
        "active" => $active 
    ]);
  });
});