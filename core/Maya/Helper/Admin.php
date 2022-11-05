<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maya\Helper;

use ArrayObject;

/**
 * Admin Helper class.
 */
class Admin extends \Lime\Helper {

    public $data;
    public $options;
    public $user;

    public $favicon;

    public function initialize(){

        $this->data =  new \ContainerArray();
        $this->options = [];
        $this->user = $this->app->module('maya')->getUser();

        // unset security related information
        if ($this->user) {
            unset($this->user['password'], $this->user['api_key'], $this->user['_reset_token']);
        }

        $this->user['data'] = new \ContainerArray(isset($this->user['data']) && is_array($this->user['data']) ? $this->user['data']:[]);
    }

    public function init() {

        // extend lexy parser
        $this->app->renderer->extend(function($content){
            return preg_replace('/(\s*)@hasaccess\?\((.+?)\)/', '$1<?php if ($app->module("maya")->hasaccess($2)) { ?>', $content);
        });

        $languages = [];
        $langDefaultLabel = 'Default';

        foreach ($this->app->retrieve('config/languages', []) as $key => $val) {

            if (is_numeric($key)) $key = $val;

            if ($key == 'default') {
                $langDefaultLabel = $val;
            } else {
                $languages[] = ['code'=>$key, 'label'=>$val];
            }
        }

        $this->data->extend([

            'maya' => json_decode($this->app->helper('fs')->read('#root:package.json'), true),

            /**
             * Admin assets
             */
            'assets'  => new ArrayObject(array_merge($this->app['app.assets.base'], [

                // uikit components
                'assets:lib/uikit/js/components/autocomplete.min.js',
                'assets:lib/uikit/js/components/tooltip.min.js',

                // app related
                'assets:app/js/bootstrap.js'

            ], $this->app->retrieve('config/app.assets.backend', []))),

            /**
             * web components
             */
            'components' => new ArrayObject([]),

            /**
             * admin menus
             */
            'menu.modules' => new ArrayObject([]),

            /**
             * extract to App.$data
             */
            'extract' => [
                'user'      => $this->user,
                'locale'    => $this->app->helper('i18n')->locale,
                'site_url'  => $this->app->pathToUrl('site:'),
                'languages' => $languages,
                'languageDefaultLabel' => $langDefaultLabel,
                'groups' => $this->app->helper('acl')->getGroups(),
                'maxUploadSize' => $this->app->helper('utils')->getMaxUploadSize(),

                'acl' => [
                    'finder' => $this->app->retrieve('finder', true) && $this->app->module('maya')->hasaccess('maya', 'finder')
                ]
            ]
        ]);
    }

    public function favicon() {
        
        if (!$this->favicon) return;

        $favicon = $this->favicon;
        $color = null;

        if (is_array($this->favicon)) {
            $favicon = $this->favicon['path'];
            $color = $this->favicon['color'] ?? null;
        }

        $ext = \strtolower(pathinfo($favicon, PATHINFO_EXTENSION));

        if (!$ext) return;

        $type = $ext == 'svg' ? 'image/svg+xml':"image/{$ext}";

        if (strpos($favicon, ':') && !preg_match('/^(\/|http\:|https\:)\//', $favicon)) {
            $path = $this->app->path($favicon);
            if (!$path) return;
            $favicon = $this->app->baseUrl($favicon);

            if ($ext=='svg' && $color) {
                $svg = file_get_contents($path);
                $svg = preg_replace('/fill="(.*?)"/', 'fill="'.$color.'"', $svg);
                $favicon = 'data:image/svg+xml;base64,'.base64_encode($svg);
            }
        }

        return '<link rel="icon" type="'.$type.'" href="'.$favicon.'" app-icon="true">';
    }

    public function addMenuItem($menu, $data) {

        $this->data["menu.{$menu}"]->append(array_merge([
            'label' => '',
            'icon'  => 'cube',
            'route' => '/',
            'active' => false
        ], $data));

        return $this;
    }

    public function addAssets($assets) {

        foreach ((array)$assets as $asset) {

            if (preg_match('/\.(js|css)$/i', $asset)) {
                $this->data['assets']->append($asset);
            } elseif(preg_match('/\.tag$/i', $asset)) {
                $this->data['components']->append($asset);
            }
        }

        return $this;
    }

    public function extractVar($key, $value) {

        $this->data["extract/{$key}"] = $value;
    }


    public function getOption($key, $default = null) {

        if (!isset($this->options[$key])) {

            $this->options[$key] = $this->app->storage->getKey("maya/options", $key, $default);
        }

        return $this->options[$key];
    }

    public function setOption($key, $value) {

        $this->options[$key] = $value;
        $this->app->storage->setKey("maya/options", $key, $value);

        return $value;
    }

    public function getUserOption($key, $default = null) {

        return $this->user['data']->get($key, $default);
    }

    public function setUserOption($key, $value) {

        $this->user['data']->set($key, $value);

        return $this->app->module('maya')->updateUserOption($key, $value);
    }

    public function isResourceLocked($resourceId, $ttl = null) {
        $ttl  = $ttl ?? 300;
        $meta = $this->app->memory->findOneById("locked",$resourceId);
        if ($meta && ($meta['time'] + $ttl) < time()) {
            $this->app->memory->removeById("locked",$resourceId);
            $meta = false;
        }

        if ($meta) {
            return $meta;
        }

        return false;
    }

    public function isResourceEditableByCurrentUser($resourceId, &$meta = null) {

        $meta = $this->isResourceLocked($resourceId);

        if (!$meta) {
            return true;
        }

        $user = $this->app->module('maya')->getUser();

        if ($meta['user']['_id'] == $user['_id'] && $meta['sid'] == md5(session_id())) {
            return true;
        }

        return false;
    }

    public function lockResourceId($resourceId, $user = null) {

        if (!$resourceId) {
            return false;
        }

        $user = $user ?? $this->app->module('maya')->getUser();

        if (!$user) {
            return false;
        }

        $meta = [
            '_id'  => $resourceId,
            'user' => ['_id' => $user['_id'], 'name' => $user['name'], 'user' => $user['user'], 'email' => $user['email']],
            'sid'  => md5(session_id()),
            'time' => time()
        ];
        $locked = boolval($this->app->memory->count("locked", ['_id'=> $resourceId, "user._id"=>['$ne'=>$user['_id']]]));
        if($locked) return $locked;
        $locked = boolval($this->app->memory->count("locked", ['_id'=> $resourceId, "user._id"=>$user['_id']]));

        if(!$locked){
            $this->app->memory->insert("locked", $meta);
        }
        return true;
    }

    public function unlockResourceId($resourceId) {
        $this->app->memory->removeById("locked",$resourceId);
        return true;
    }
    public function unlockUserAllResources($user = null) {
        $user = $user ?? $this->app->module('maya')->getUser();

        if (!$user) {
            return false;
        }
        $ret = $this->app->memory->remove("locked",["user._id"=>['$ne'=>$user['_id']]]);
        return boolval($ret);
    }

    public function denyRequest() {

        if ($this->app->module('maya')->getUser()) {
            $this->app->response->status = 401;
        } else {
            $this->app->response->status = 404;
        }

        return '';
    }
    public function addDashboardWidget($name,$content,$side ="main") {
        $this->app->on('admin.dashboard.widgets', function($widgets) use($name, $content, $side) {
            $widgets[] = [
                'name'    => $name,
                'content' => $content,
                'area'    => $side == 'left' ? "aside-left" : ($side == 'right' ? "aside-right" : 'main')
            ];
        
        });
    }
}
