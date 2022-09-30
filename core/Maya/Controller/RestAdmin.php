<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maya\Controller;

class RestAdmin extends \Maya\AuthController {

    public function __construct($app) {

        parent::__construct($app);

        if (!$this->module('maya')->hasaccess('maya', 'rest')) {
            return $this->helper('admin')->denyRequest();
        }
    }


    public function index() {

        $keys = $this->app->module('maya')->loadApiKeys();

        return $this->render('maya:views/restadmin/index.php', compact('keys'));
    }


    public function save() {
        
        $data = $this->param('data', false);

        if (!$data) {
            return false;
        }

        return ['success' => $this->app->module('maya')->saveApiKeys($data)];
    }

}
