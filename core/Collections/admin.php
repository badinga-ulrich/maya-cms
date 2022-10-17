<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->on('admin.init', function() {

    $this->helper('admin')->addAssets('collections:assets/field-collectionlink.tag');
    $this->helper('admin')->addAssets('collections:assets/field-collectionlinkselect.tag');
    $this->helper('admin')->addAssets('collections:assets/link-collectionitem.js');
    $this->helper('admin')->addAssets('collections:assets/field-multiplecollectionlink.js');
    $this->helper('admin')->addAssets('collections:assets/field-multiplecollectionlink.tag');

    if (!$this->module('maya')->getGroupRights('collections') && !$this->module('collections')->getCollectionsInGroup()) {

        $this->bind('/collections/*', function() {
            return $this('admin')->denyRequest();
        });

        return;
    }

    // bind admin routes /collections/*
    $this->bindClass('Collections\\Controller\\Trash', 'collections/trash');
    $this->bindClass('Collections\\Controller\\Import', 'collections/import');
    $this->bindClass('Collections\\Controller\\Utils', 'collections/utils');
    $this->bindClass('Collections\\Controller\\Admin', 'collections');

    $active = strpos($this['route'], '/collections') === 0;

    // add to modules menu
    $this->helper('admin')->addMenuItem('modules', [
        'label' => 'Collections',
        'icon'  => 'collections:icon.svg',
        'route' => '/collections',
        'active' => $active
    ]);

    if ($active) {
        $this->helper('admin')->favicon = 'collections:icon.svg';
    }

    /**
     * listen to app search to filter collections
     */
    $this->on('maya.search', function($search, $list) {

        foreach ($this->module('collections')->getCollectionsInGroup() as $collection => $meta) {

            if (stripos($collection, $search)!==false || stripos($meta['label'], $search)!==false) {

                $list[] = [
                    'icon'  => 'database',
                    'title' => $meta['label'] ? $meta['label'] : $meta['name'],
                    'url'   => $this->routeUrl('/collections/entries/'.$meta['name'])
                ];
            }
        }
    });

    // dashboard widgets
    $this->on("admin.dashboard.widgets", function($widgets) {

        $collections = $this->module("collections")->getCollectionsInGroup(null, false);

        $widgets[] = [
            "name"    => "collections",
            "content" => $this->view("collections:views/widgets/dashboard.php", compact('collections')),
            "area"    => 'aside-left'
        ];

    }, 100);

    // update assets references on file update
    $this->on('maya.assets.updatefile', function($asset) {

        $id = $asset['_id'];
        $collections = $this->module('collections')->collections();
        $filter = ($this->storage->type == 'mongolite') ?
            function ($doc) use ($id) { return strpos(json_encode($doc), $id) !== false;}
            :
            ['$where' => "function() { return JSON.stringify(this).indexOf('{$id}') > -1; }"]
        ;

        $update = function(&$items) use($asset, $id, &$update) {

            if (!is_array($items)) return $items;

            foreach ($items as $k => &$v) {
                if (!is_array($v)) continue;
                if (is_array($items[$k])) $items[$k] = $update($items[$k]);
                if (isset($v['_id']) && $v['_id'] == $id) $items[$k] = $asset;
            }
            return $items;
        };

        foreach ($collections as $name => $meta) {

            $collection = "collections/{$meta['_id']}";
            $entries = $this->storage->find($collection, ['filter' => $filter])->toArray();

            if (!count($entries)) continue;

            $entries = $update($entries);

            foreach ($entries as $entry) {
                $this->storage->save($collection, $entry);
            }
        }
    });
    $collections = ($this->module('collections')->collections());
    foreach ($collections as $name => $collection) {
        $_bootstrap = maya()->path('#storage:collections/views/'.$collection['name'].'.bootstrap.php');
        if(isset($collection['views'],$collection['views']['bootstrap']) && $collection['views']['bootstrap'] && $_bootstrap){
            try {
                include($_bootstrap);
            } catch (\Throwable $th) {
                // var_dump($th); exit;
            }
        }
    }
});


