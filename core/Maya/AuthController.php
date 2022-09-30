<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maya;

class AuthController extends \LimeExtra\Controller {

    protected $layout = 'maya:views/layouts/app.php';
    protected $user;

    public function __construct($app) {

        $user = $app->module('maya')->getUser();

        if (!$user) {
            if ($app->request->is('ajax') || MAYA_API_REQUEST) {
                $app->response->body = '{"error": "404", "message":"File not found"}';
            } else {
                // try to send public files
                $app->module('maya')->publicFile($app->request);
                $app->response->body = $app->view('maya:views/errors/404.php');
            }
            $this->trigger('maya.request.error', ['404']);

            $app->stop();
        }

        parent::__construct($app);

        $this->user  = $user;
        $app['user'] = $user;

        $controller = \strtolower(\str_replace('\\', '.', \get_class($this)));

        $app->trigger("app.{$controller}.init", [$this]);

    }

}
