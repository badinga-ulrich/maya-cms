<?php

namespace Documentation\Controller;


class Admin extends \Maya\AuthController {

    public function index(){
      maya()->module('documentation')->docViewer();
    }

    public function admin() {
        return $this->render('documentation:views/iframe.php');
    }

}
