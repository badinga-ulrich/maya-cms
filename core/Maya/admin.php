<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Helpers
 */

// because auto-load not ready yet
include(__DIR__.'/Helper/Admin.php');

$app->helpers['admin'] = 'Maya\\Helper\\Admin';
$app->helpers['csrf']  = 'Maya\\Helper\\Csrf';

// init + load i18n
$app->on('before', function() {

    $this->helper('i18n')->locale = $this->retrieve('i18n', 'en');

    $locale = $this->module('maya')->getUser('i18n', $this->helper('i18n')->locale);

    if ($translationspath = $this->path("#config:maya/i18n/{$locale}.php")) {
        $this->helper('i18n')->locale = $locale;
        $this->helper('i18n')->load($translationspath, $locale);
    }

    $this->bind('/maya.i18n.data', function() {
        $this->response->mime = 'js';
        $data = $this->helper('i18n')->data($this->helper('i18n')->locale);
        return 'if (i18n) { i18n.register('.(count($data) ? json_encode($data):'{}').'); }';
    });
});

/**
 * register assets
 */

$assets = [

    // polyfills
    'assets:polyfills/dom4.js',
    'assets:polyfills/document-register-element.js',
    'assets:polyfills/URLSearchParams.js',

    // libs
    'assets:lib/moment.js',
    'assets:lib/jquery.js',
    'assets:lib/lodash.js',
    'assets:lib/chart.min.js',
    'assets:lib/print-el.js',
    'assets:lib/riot/riot.js',
    'assets:lib/riot/riot.bind.js',
    'assets:lib/riot/riot.view.js',
    'assets:lib/uikit/js/uikit.min.js',
    'assets:lib/uikit/js/components/notify.min.js',
    'assets:lib/uikit/js/components/tooltip.min.js',
    'assets:lib/uikit/js/components/lightbox.min.js',
    'assets:lib/uikit/js/components/sortable.min.js',
    'assets:lib/uikit/js/components/sticky.min.js',
    'assets:lib/mousetrap.js',
    'assets:lib/storage.js',
    'assets:lib/i18n.js',

    // app
    'assets:app/js/app.js',
    'assets:app/js/app.utils.js',
    'assets:app/js/codemirror.js',
    'assets:app/components/cp-actionbar.js',
    'assets:app/components/cp-fieldcontainer.js',
    'maya:assets/components.js',
    'maya:assets/maya.js',

    'assets:app/css/style.css',
];

// load custom css style
if ($app->path('#config:maya/style.css')) {
    $assets[] = '#config:maya/style.css';
}

$app['app.assets.base'] = $assets;


/**
 * register routes
 */

$app->bindClass('Maya\\Controller\\Utils', 'maya/utils');
$app->bindClass('Maya\\Controller\\Base', 'maya');
$app->bindClass('Maya\\Controller\\Settings', 'settings');
$app->bindClass('Maya\\Controller\\Accounts', 'accounts');
$app->bindClass('Maya\\Controller\\Auth', 'auth');
$app->bindClass('Maya\\Controller\\Media', 'media');
$app->bindClass('Maya\\Controller\\Assets', 'assetsmanager');
$app->bindClass('Maya\\Controller\\RestAdmin', 'restadmin');
$app->bindClass('Maya\\Controller\\Webhooks', 'webhooks');


/**
 * Check user session for backend ui
 */
$app->on('maya.auth.setuser', function($user, $permanent) {
    if (!$permanent) return;
    $this('session')->write('maya.session.time', time());
});

// update session time
$app->on('admin.init', function() {
    if ($this['route'] != '/check-backend-session' && isset($_SESSION['maya.session.time'])) {
        $_SESSION['maya.session.time'] = time();
    }
});

// check + validate session time
$app->bind('/check-backend-session', function() {

    session_write_close();

    $user = $this->module('maya')->getUser();
    $status = true;

    if (!$user) {
        $status = false;
    }

    // check for inactivity: 45min by default
    if ($status && isset($_SESSION['maya.session.time']) && ($_SESSION['maya.session.time'] + $this->retrieve('session.lifetime', 2700) < time())) {
        $this->module('maya')->logout();
        $status = false;
    }

    return ['status' => $status];
});


/**
 * on admint init
 */
$app->on('admin.init', function() {

    // bind finder
    $this->bind('/finder', function() {

        $this->layout = 'maya:views/layouts/app.php';
        $this["user"] = $this->module('maya')->getUser();
        return $this->view('maya:views/base/finder.php');

    }, $this->module('maya')->hasaccess('maya', 'finder'));

}, 0);


/**
 * listen to app search to filter accounts
 */

$app->on('maya.search', function($search, $list) {

    if (!$this->module('maya')->hasaccess('maya', 'accounts')) {
        return;
    }

    foreach ($this->storage->find('maya/accounts') as $a) {

        if (strripos($a['name'].' '.$a['user'], $search)!==false){
            $list[] = [
                'icon'  => 'user',
                'title' => $a['name'],
                'url'   => $this->routeUrl('/accounts/account/'.$a['_id'])
            ];
        }
    }
});

// dashboard widgets


$app->on('admin.dashboard.widgets', function($widgets) {

    $title = $this('i18n')->get('Today');

    $widgets[] = [
        'name'    => 'time',
        'content' => $this->view('maya:views/widgets/datetime.php', compact('title')),
        'area'    => 'main'
    ];

}, 100);

/**
 * handle error pages
 */
$app->on('after', function() {

    switch ($this->response->status) {

        case 401:

            if ($this->request->is('ajax') || MAYA_API_REQUEST) {
                $this->response->body = '{"error": "401", "message":"Unauthorized"}';
            } else {
                $this->response->body = $this->view('maya:views/errors/401.php');
            }

            $this->trigger('maya.request.error', ['401']);
            break;

        case 404:

            if ($this->request->is('ajax') || MAYA_API_REQUEST) {
                $this->response->body = '{"error": "404", "message":"File not found"}';
            } else {
                // try to send public files
                $this->module('maya')->publicFile($this->request);
                $this->response->body = $this->view('maya:views/errors/404.php');
            }

            $this->trigger('maya.request.error', ['404']);
            break;
    }

     /**
     * send some debug information
     * back to client (visible in the network panel)
     */
    if ($this['debug'] && !headers_sent()) {

        /**
        * some system info
        */

        $DURATION_TIME = microtime(true) - MAYA_START_TIME;
        $MEMORY_USAGE  = memory_get_peak_usage(false)/1024/1024;

        header('MAYA_DURATION_TIME: '.$DURATION_TIME.'sec');
        header('MAYA_MEMORY_USAGE: '.$MEMORY_USAGE.'mb');
        header('MAYA_LOADED_FILES: '.count(get_included_files()));
    }
});

// load package info
$app['maya'] = json_decode($app('fs')->read('#root:package.json'), true);

// init app helper
$app('admin')->init();
