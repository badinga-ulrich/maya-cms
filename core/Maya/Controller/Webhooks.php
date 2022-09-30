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

class Webhooks extends \Maya\AuthController {

    public function __construct($app) {

        parent::__construct($app);

        if (!$this->module('maya')->hasaccess('maya', 'webhooks')) {
            return $this->helper('admin')->denyRequest();
        }
    }

    public function index() {

        $webhooks = $this->app->storage->find('maya/webhooks', [
            'sort' => ['name' => 1]
        ])->toArray();

        return $this->render('maya:views/webhooks/index.php', compact('webhooks'));
    }

    public function webhook($id = null) {

        $webhook = [
            'name' => '',
            'url'  => '',
            'auth' => ['user'=>'', 'pass'=>''],
            'headers' => [],
            'events' => [],
            'active' => true
        ];

        if ($id) {

            $webhook = $this->app->storage->findOne('maya/webhooks', ['_id' => $id]);

            if (!$webhook) {
                return false;
            }
        }

        $triggers = new \ArrayObject([
            'admin.init',
            'app.{$controller}.init',
            'maya.account.login',
            'maya.account.logout',
            'maya.api.authenticate',
            'maya.api.erroronrequest',
            'maya.assets.list',
            'maya.assets.remove',
            'maya.assets.save',
            'maya.bootstrap',
            'maya.clearcache',
            'maya.export',
            'maya.import',
            'maya.media.removefiles',
            'maya.media.rename',
            'maya.media.upload',
            'maya.request.error',
            'maya.rest.init',
            'maya.update.after',
            'maya.update.before',
            'shutdown',
        ]);

        $this->app->trigger('maya.webhook.events', [$triggers]);

        $triggers = $triggers->getArrayCopy();

        return $this->render('maya:views/webhooks/webhook.php', compact('webhook', 'triggers'));
    }

    public function save() {

        if ($data = $this->param('webhook', false)) {

            $data['_modified'] = time();

            if (!isset($data['_id'])) {
                $data['_created'] = $data['_modified'];
            }

            $this->app->storage->save('maya/webhooks', $data);

            // invalidate cache
            if ($cache = $this->app->path('#tmp:webhooks.cache.php')) {
                @unlink($cache);
            }

            return json_encode($data);
        }

        return false;

    }

    public function remove() {

        if ($data = $this->param('webhook', false)) {

            $this->app->storage->remove('maya/webhooks', ['_id'=>$data['_id']]);

            // invalidate cache
            if ($cache = $this->app->path('#tmp:webhooks.cache.php')) {
                @unlink($cache);
            }

            return true;
        }

        return false;

    }
}
