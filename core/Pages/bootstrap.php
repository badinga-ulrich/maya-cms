<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$this->module('pages')->extend([

    'createPage' => function($name, $data = []) {

        if (!trim($name)) {
            return false;
        }

        $configpath = $this->app->path('#storage:').'/page';

        if (!$this->app->path('#storage:page')) {

            if (!$this->app->helper('fs')->mkdir($configpath)) {
                return false;
            }
        }

        if ($this->exists($name)) {
            return false;
        }

        $time = time();

        $page = array_replace_recursive([
            'name'      => $name,
            'label'     => $name,
            '_id'       => $name,
            'fields'    => [],
            'data'      => null,
            '_created'  => $time,
            '_modified' => $time
        ], $data);

        $this->app->trigger('page.save.before', [$page]);
        $this->app->trigger("page.save.before.{$name}", [$page]);

        $export = var_export($page, true);

        if (!$this->app->helper('fs')->write("#storage:page/{$name}.page.php", "<?php\n return {$export};")) {
            return false;
        }

        $this->app->trigger('page.save.after', [$page]);
        $this->app->trigger("page.save.after.{$name}", [$page]);

        return $page;
    },

    'updatePage' => function($name, $data) {

        $metapath = $this->app->path("#storage:page/{$name}.page.php");

        if (!$metapath) {
            return false;
        }

        $data['_modified'] = time();

        $page  = include($metapath);
        $page  = array_merge($page, $data);


        $this->app->trigger('page.save.before', [$page]);
        $this->app->trigger("page.save.before.{$name}", [$page]);

        $export  = var_export($page, true);

        if (!$this->app->helper('fs')->write($metapath, "<?php\n return {$export};")) {
            return false;
        }

        $this->app->trigger('page.save.after', [$page]);
        $this->app->trigger("page.save.after.{$name}", [$page]);

        if (function_exists('opcache_reset')) opcache_reset();

        return $page;
    },

    'savePage' => function($name, $data) {

        if (!trim($name)) {
            return false;
        }

        return isset($data['_id']) ? $this->updatePage($name, $data) : $this->createPage($name, $data);
    },

    'removePage' => function($name) {

        if ($page = $this->page($name)) {

            $this->app->helper('fs')->delete("#storage:page/{$name}.page.php");
            $this->app->storage->removeKey('pages', $name);

            $this->app->trigger('page.remove', [$page]);
            $this->app->trigger("page.remove.{$name}", [$page]);

            return true;
        }

        return false;
    },

    'saveData' => function($name, $data, $options = []) {

        if ($page = $this->page($name)) {

            $this->app->trigger('page.saveData.before', [$page, &$data]);
            $this->app->trigger("page.saveData.before.{$name}", [$page, &$data]);

            $this->app->storage->setKey('pages', $name, $data);

            $this->app->trigger('page.saveData.after', [$page, $data]);
            $this->app->trigger("page.saveData.after.{$name}", [$page, $data]);

            if (isset($options['revision']) && $options['revision']) {
                $this->app->helper('revisions')->add($page['_id'], $data, "pages/{$page['name']}", true);
            }

            return $data;
        }

        return false;
    },

    'getData' => function($name, $options = []) {

        if ($page = $this->page($name)) {

            $options = array_merge([
                'user' => false,
                'populate' => false,
                'lang' => false,
                'ignoreDefaultFallback' => false
            ], $options);

            $data = $this->app->storage->getKey('pages', $name);
            if (is_null($data)) {
                $data = [];
                if (isset($page['fields']) && is_array($page['fields'])) {
                    foreach ($page['fields'] as $f) $data[$f['name']] = null;
                }
            }

            $data = $this->_filterFields($data, $page, $options);

            if ($options['populate'] && function_exists('maya_populate_collection')) {

                $fieldsFilter = [];

                if ($options['user']) $fieldsFilter['user'] = $options['user'];
                if ($options['lang']) $fieldsFilter['lang'] = $options['lang'];

                $_items = [$data];
                $_items = maya_populate_collection($_items, intval($options['populate']), 0, $fieldsFilter);
                $data = $_items[0];
            }

            $this->app->trigger('page.getData.after', [$page, &$data]);
            $this->app->trigger("page.getData.after.{$name}", [$page, &$data]);

            return $data;
        }

        return null;
    },

    'pages' => function() {

        $pages = [];

        foreach ($this->app->helper("fs")->ls('*.page.php', '#storage:page') as $path) {

            $store = include($path->getPathName());
            $pages[$store['name']] = $store;
        }

        return $pages;
    },

    'exists' => function($name) {
        return $this->app->path("#storage:page/{$name}.page.php");
    },

    'page' => function($name) {

        static $page; // cache

        if (is_null($page)) {
            $page = [];
        }

        if (!is_string($name)) {
            return false;
        }

        if (!isset($page[$name])) {

            $page[$name] = false;

            if ($path = $this->exists($name)) {
                $page[$name] = include($path);
            }
        }

        return $page[$name];
    },

    'getFieldValue' => function($page, $fieldname, $default = null, $options = []) {

        $data = $this->page($page);
        return ($data && isset($data[$fieldname])) ? $data[$fieldname] : $default;
    },

    'setFieldValue' => function($page, $fieldName, $fieldValue = null, $options = []) {

        $data = $this->page($page, $options);
        if(isset($data[$fieldName]) || (isset($options["createIfNotExists"]) && $options["createIfNotExists"])){
            $data[$fieldName] = $fieldValue;
        }
        $data = $this->updatePage($page, $data);
        return isset($data[$fieldName]);
    },

    '_filterFields' => function($data, $page, $filter) {

        static $cache;
        static $languages;

        if (null === $data) {
            return $data;
        }

        $filter = array_merge([
            'user' => false,
            'lang' => false,
            'ignoreDefaultFallback' => false
        ], $filter);

        extract($filter);

        if (null === $cache) {
            $cache = [];
        }

        if (null === $languages) {

            $languages = [];

            foreach ($this->app->retrieve('config/languages', []) as $key => $val) {
                if (is_numeric($key)) $key = $val;
                $languages[] = $key;
            }
        }

        if (is_string($page)) {
            $page = $this->collection($page);
        }

        if (!isset($cache[$page['name']])) {

            $fields = [
                'acl' => [],
                'localize' => []
            ];

            foreach ($page["fields"] as $field) {

                if (isset($field['acl']) && is_array($field['acl']) && count($field['acl'])) {
                    $fields['acl'][$field['name']] = $field['acl'];
                }

                if (isset($field['localize']) && $field['localize']) {
                    $fields['localize'][$field['name']] = true;
                }
            }

            $cache[$page['name']] = $fields;
        }

        if ($user && count($cache[$page['name']]['acl'])) {

            $aclfields = $cache[$page['name']]['acl'];

            foreach ($aclfields as $name => $acl) {

                if (!( in_array($user['group'], $acl) || in_array($user['_id'], $acl) )) {

                    unset($data[$name]);

                    if (count($languages)) {

                        foreach ($languages as $l) {
                            if (isset($data["{$name}_{$l}"])) {
                                unset($data["{$name}_{$l}"]);
                                unset($data["{$name}_{$l}_slug"]);
                            }
                        }
                    }
                }
            }
        }

        if ($lang && count($languages) && count($cache[$page['name']]['localize'])) {

            $localfields = $cache[$page['name']]['localize'];

            foreach ($localfields as $name => $local) {

                foreach ($languages as $l) {

                    if (isset($data["{$name}_{$l}"]) && $data["{$name}_{$l}"] !== '') {

                        if ($l == $lang) {

                            $data[$name] = $data["{$name}_{$l}"];

                            if (isset($data["{$name}_{$l}_slug"])) {
                                $data["{$name}_slug"] = $data["{$name}_{$l}_slug"];
                            }
                        }

                    } elseif ($l == $lang && $ignoreDefaultFallback) {

                        if ($ignoreDefaultFallback === true || (is_array($ignoreDefaultFallback) && in_array($name, $ignoreDefaultFallback))) {
                            $data[$name] = null;
                        }
                    }

                    unset($data["{$name}_{$l}"]);
                    unset($data["{$name}_{$l}_slug"]);
                }
            }
        }

        return $data;
    }

]);

// ACL
$app("acl")->addResource('pages', ['create', 'editor', 'edit', 'view', 'delete', 'manage']);

$this->module('pages')->extend([

    'getPagesInGroup' => function($group = null) {

        if (!$group) {
            $group = $this->app->module('maya')->getGroup();
        }

        $_pages = $this->pages();
        $pages = [];

        if ($this->app->module('maya')->isSuperAdmin()) {
            return $_pages;
        }

        foreach ($_pages as $page => $meta) {

            if (isset($meta['acl'][$group]['view']) && $meta['acl'][$group]['view']) {
                $pages[$page] = $meta;
            }
        }

        return $pages;
    },

    'hasaccess' => function($page, $action, $group = null) {

        $page = $this->page($page);

        if (!$page) {
            return false;
        }

        if (!$group) {
            $group = $this->app->module('maya')->getGroup();
        }

        if ($this->app->module('maya')->isSuperAdmin($group)) {
            return true;
        }

        if (isset($page['acl'][$group][$action])) {
            return $page['acl'][$group][$action];
        }

        return false;
    }
]);

// REST
if (MAYA_API_REQUEST) {

    $app->on('maya.rest.init', function($routes) {
        $routes['pages'] = 'Pages\\Controller\\RestApi';
    });

    // allow access to public collections
    $app->on('maya.api.authenticate', function($data) {

        if ($data['user'] || $data['resource'] != 'pages') return;

        if (isset($data['query']['params'][1])) {

            $page = $this->module('pages')->page($data['query']['params'][1]);

            if ($page && isset($page['acl']['public'])) {
                $data['authenticated'] = true;
                $data['user'] = ['_id' => null, 'group' => 'public'];
            }
        }
    });
}

// register events for autocomplete
$app->on('maya.webhook.events', function($triggers) {
    
    foreach([
        'page.getData.after',
        'page.getData.after.{$name}',
        'page.remove',
        'page.remove.{$name}',
        'page.save.after',
        'page.save.after.{$name}',
        'page.save.before',
        'page.save.before.{$name}',
        'page.saveData.after',
        'page.saveData.after.{$name}',
        'page.saveData.before',
        'page.saveData.before.{$name}',
    ] as &$evt) { $triggers[] = $evt; }
});
// ADMIN
if (MAYA_ADMIN_CP) {
    include_once(__DIR__.'/admin.php');
}

// CLI
if (MAYA_CLI) {
    $this->path('#cli', __DIR__.'/cli');
}


// PUBLIC URL
$app->on('maya.bootstrap', function() use($app) {
    $user = $app->module('maya')->getUser();
    if (!$user || isset($_REQUEST["view_public"])) {
        $pages = $app->module('pages')->pages();
        foreach ($pages as  $page) {
            $app->bind($page["url"], function() use($page){
                $name = $page["name"];
                $this->response->mime = 'html';
                if (!$name) {
                    return false;
                }
        
                if ($user = $this->module('maya')->getUser()) {
        
                    if (!$this->module('pages')->hasaccess($name, 'view') && !$this->module('pages')->hasaccess($name, 'view')) {
                        return $this->stop('{"error": "Unauthorized"}', 401);
                    }
                }
        
                $options = [];
        
                if ($lang = $this->param('lang', false)) $options['lang'] = $lang;
                if ($populate = $this->param('populate', false)) $options['populate'] = $populate;
                if ($ignoreDefaultFallback = $this->param('ignoreDefaultFallback', false)) $options['ignoreDefaultFallback'] = $ignoreDefaultFallback;
                if ($user) $options['user'] = $user;
        
                $data = $this->module('pages')->page($name, $options);
        
                if (!$data) {
                    return false;
                }
                return implode("\n",[
                    "<html>","<head>",
                        "<title>",$page['label'],"</title>",
                        "<style>",$data["css"] ?? "","</style>",
                      "</head>",
                      "<body>",$data["html"] ?? "","</body>",
                      "<script>",$data["js"] ?? "","</script>",
                    "</html>"]);
            });
        }
    }
});
