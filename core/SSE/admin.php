<?php

$app->on('admin.init', function() {
    $this->helper('admin')->addAssets('sse:assets/xterm.css');
    $this->helper('admin')->addAssets('sse:assets/xterm.js');
    $this->helper('admin')->addAssets('sse:assets/xterm-addon-canvas.js');
    $this->helper('admin')->addAssets('sse:assets/eventsource.js');
    /*
    * add info
    */
    $this->on('maya.settings.infopage.aside.menu', function() {
      $this->renderView("sse:views/menu/info.php");
    });

    $this->on('maya.settings.infopage.main.menu', function() {
      $this->renderView("sse:views/info.php");
    });

    $this->on('maya.settings.infopage.script', function() {
      $this->renderView("sse:views/info.js");
    });
});