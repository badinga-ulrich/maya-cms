<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

spl_autoload_register(function($class){
    if(preg_match("#^Pages\\\\#",$class)){
      $class = str_ireplace("pages","",$class);
      $class_path = preg_replace('#[/\\\\]+#', '/',__DIR__.'/'.$class.'.php');
      if(file_exists($class_path)) include_once($class_path);
    }
});
$app->on('admin.init', function() {
    // $user = $this->module('maya')->getUser();
    // var_dump(
    //     "PAGES", 
    //     $user,
    //     $this->module('maya')->getGroupRights()
    // );exit;
    
    if (!$this->module('maya')->getGroupRights('pages') && !$this->module('pages')->getPagesInGroup()) {

        $this->bind('/pages/*', function() {
            return $this('admin')->denyRequest();
        });

        return;
    }

    // bind admin routes /page/*
    $this->bindClass('Pages\\Controller\\Admin', 'pages');

    $active = strpos($this['route'], '/pages') === 0;

    // add to modules menu
    $this->helper('admin')->addMenuItem('modules', [
        'label' => 'Pages',
        'icon'  => 'pages:icon.svg',
        'route' => '/pages',
        'active' => $active
    ]);

    if ($active) {
        $this->helper('admin')->favicon = 'pages:icon.svg';
    }

    /**
     * listen to app search to filter page
     */
    $this->on('maya.search', function($search, $list) {

        foreach ($this->module('pages')->getPagesInGroup() as $page => $meta) {

            if (stripos($page, $search)!==false || stripos($meta['label'], $search)!==false) {

                $list[] = [
                    'icon'  => 'th',
                    'title' => $meta['label'] ? $meta['label'] : $meta['name'],
                    'url'   => $this->routeUrl('/pages/page/'.$meta['name'])
                ];
            }
        }
    });

    // dashboard widgets
    $this->on('admin.dashboard.widgets', function($widgets) {

        $pages = $this->module('pages')->getPagesInGroup();

        $widgets[] = [
            'name'    => 'page',
            'content' => $this->view('pages:views/widgets/dashboard.php', compact('pages')),
            'area'    => 'aside-right'
        ];

    }, 100);



    // dashboard widgets
    $this->on('maya.groups.settings.generic', function($widgets) {
        $this->renderView("pages:views/widgets/group.php");
    }, 100);


    // update assets references on file update
    $this->on('maya.assets.updatefile', function($asset) {

        $id = $asset['_id'];
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

        $pages = $this->storage->find('pages', ['filter' => $filter])->toArray();

        if (!count($pages)) return;

        $pages = $update($pages);

        foreach ($pages as $page) {
            $this->storage->save('pages', $page);
        }
    });
});
