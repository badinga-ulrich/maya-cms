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

    if (!$this->module('maya')->getGroupRights('forms')) {

        $this->bind('/forms/*', function() {
            return $this('admin')->denyRequest();
        });

        return;
    }

    // bind admin routes /forms/*
    $this->bindClass('Forms\\Controller\\Admin', 'forms');

    $active = strpos($this['route'], '/forms') === 0;

    // add to modules menu
    $this->helper('admin')->addMenuItem('modules', [
        'label' => 'Forms',
        'icon'  => 'forms:icon.svg',
        'route' => '/forms',
        'active' => $active
    ]);

    if ($active) {
        $this->helper('admin')->favicon = 'forms:icon.svg';
    } 

    /**
     * listen to app search to filter forms
     */
    $this->on('maya.search', function($search, $list) {

        foreach ($this->module('forms')->forms() as $form => $meta) {

            if (stripos($form, $search)!==false || stripos($meta['label'], $search)!==false) {

                $list[] = [
                    'icon'  => 'inbox',
                    'title' => $meta['label'] ? $meta['label'] : $meta['name'],
                    'url'   => $this->routeUrl('/forms/entries/'.$meta['name'])
                ];
            }
        }
    });


    // dashboard widgets
    $this->on('admin.dashboard.widgets', function($widgets) {

        $forms = $this->module('forms')->forms(false);

        $widgets[] = [
            'name'    => 'forms',
            'content' => $this->view('forms:views/widgets/dashboard.php', compact('forms')),
            'area'    => 'aside-left'
        ];

    }, 100);


});
