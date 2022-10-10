<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pages\Controller;


class Admin extends \Maya\AuthController {

    public function index() {

        $_pages = $this->module('pages')->getPagesInGroup();
        $pages  = [];

        foreach ($_pages as $name => $meta) {

            $meta['allowed'] = [
                'delete' => $this->module('maya')->hasaccess('pages', 'delete'),
                'create' => $this->module('maya')->hasaccess('pages', 'create'),
                'page_edit' => $this->module('pages')->hasaccess($name, 'edit'),
                'page_editor' => $this->module('pages')->hasaccess($name, 'editor')
            ];

            $pages[] = [
              'name'  => $name,
              'label' => isset($meta['label']) && $meta['label'] ? $meta['label'] : $name,
              'meta'  => $meta
            ];
        }

        // sort pages
        usort($pages, function($a, $b) {
            return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
        });

        return $this->render('pages:views/index.php', compact('pages'));
    }

    public function page($name = null) {

        if ($name && !$this->module('pages')->hasaccess($name, 'edit')) {
            return $this->helper('admin')->denyRequest();
        }

        if (!$name && !$this->module('maya')->hasaccess('pages', 'create')) {
            return $this->helper('admin')->denyRequest();
        }

        $page = [ 'name'=>'', 'description' => '', 'fields'=>[], 'data' => null];

        if ($name) {

            $page = $this->module('pages')->page($name);

            if (!$page) {
                return false;
            }

            if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($page['_id'], $meta)) {
                return $this->render('maya:views/base/locked.php', compact('meta'));
            }

            $this->app->helper('admin')->lockResourceId($page['_id']);
        }

        // acl groups
        $aclgroups = [];

        foreach ($this->app->helper('acl')->getGroups() as $group => $superAdmin) {

            if (!$superAdmin) $aclgroups[] = $group;
        }

        return $this->render('pages:views/page.php', compact('page', 'aclgroups'));
    }

    public function editor($name = null) {

        if (!$name) {
            return false;
        }

        $page = $this->module('pages')->page($name);

        if (!$page) {
            return false;
        }

        if (!$this->module('pages')->hasaccess($page['name'], 'editor')) {
            return $this->helper('admin')->denyRequest();
        }

        $page = array_merge([
            'sortable' => false,
            'color' => '',
            'icon' => '',
            'description' => ''
        ], $page);

        $this->app->helper('admin')->favicon = [
            'path' => 'pages:icon.svg',
            'color' => $page['color']
        ];

        $lockId = "page_{$page['name']}";

        if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($lockId, $meta)) {
            return $this->render('pages:views/locked.php', compact('meta', 'page'));
        }

        $data = $this->module('pages')->getData($name);

        $this->app->helper('admin')->lockResourceId($lockId);

        return $this->render('pages:views/editor.php', compact('page', 'data'));

    }

    public function remove_page($page) {

        $page = $this->module('pages')->page($page);

        if (!$page) {
            return false;
        }

        if (!$this->module('pages')->hasaccess($page['name'], 'delete')) {
            return $this->helper('admin')->denyRequest();
        }

        $this->module('pages')->removePage($page['name']);

        return ['success' => true];
    }

    public function update_data($page) {

        $page = $this->module('pages')->page($page);
        $data = $this->param('data');

        if (!$page || !$data) {
            return false;
        }

        if (!$this->module('pages')->hasaccess($page['name'], 'editor')) {
            return $this->helper('admin')->denyRequest();
        }

        $lockId = "page_{$page['name']}";

        if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($lockId)) {
            $this->stop(['error' => "Saving failed! Page is locked!"], 412);
        }

        $data['_mby'] = $this->module('maya')->getUser('_id');

        if (isset($data['_by'])) {
            $_data = $this->module('pages')->getData($page['name']);
            $revision = !(json_encode($_data) == json_encode($data));
        } else {
            $data['_by'] = $data['_mby'];
            $revision = true;
        }

        $data = $this->module('pages')->saveData($page['name'], $data, ['revision' => $revision]);

        $this->app->helper('admin')->lockResourceId($lockId);

        return ['data' => $data];
    }

    public function revisions($page, $id) {

        if (!$this->module('pages')->hasaccess($page, 'editor')) {
            return $this->helper('admin')->denyRequest();
        }

        $page = $this->module('pages')->page($page);

        if (!$page) {
            return false;
        }

        $data = $this->app->storage->getKey('pages', $page['name']);

        if (!$data) {
            return false;
        }

        $languages = $this->app->retrieve('config/languages', []);

        $allowedFields = [];

        foreach ($page['fields'] as $field) {

            if (isset($field['acl']) && is_array($field['acl']) && count($field['acl'])) {

                if (!( in_array($user['group'], $field['acl']) || in_array($user['_id'], $field['acl']) )) {
                    continue;
                }
            }

            $allowedFields[] = $field['name'];

            if (isset($field['localize']) && $field['localize']) {
                foreach ($languages as $key => $val) {
                    if (is_numeric($key)) $key = $val;
                    $allowedFields[] = "{$field['name']}_{$key}";
                }
            }
        }

        $revisions = $this->app->helper('revisions')->getList($id);

        return $this->render('pages:views/revisions.php', compact('page', 'data', 'revisions', 'id', 'allowedFields'));
    }
}
