<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Collections\Controller;


class Admin extends \Maya\AuthController
{


    public function index()
    {

        $_collections = $this->module('collections')->getCollectionsInGroup(null, false);
        $collections = [];

        foreach ($_collections as $collection => $meta) {

            $meta['allowed'] = [
                'delete' => $this->module('maya')->hasaccess('collections', 'delete'),
                'create' => $this->module('maya')->hasaccess('collections', 'create'),
                'edit' => $this->module('collections')->hasaccess($collection, 'collection_edit'),
                'entries_create' => $this->module('collections')->hasaccess($collection, 'collection_create'),
                'entries_delete' => $this->module('collections')->hasaccess($collection, 'entries_delete'),
            ];

            $meta['itemsCount'] = null;

            $collections[] = [
                'name' => $collection,
                'label' => isset($meta['label']) && $meta['label'] ? $meta['label'] : $collection,
                'meta' => $meta
            ];
        }

        // sort collections
        usort(
            $collections, function ($a, $b) {
                return mb_strtolower($a['label']) <=> mb_strtolower($b['label']);
            }
        );

        return $this->render('collections:views/index.php', compact('collections'));
    }

    public function _collections()
    {
        return $this->module('collections')->collections();
    }

    public function _find()
    {

        if ($this->param('collection') && $this->param('options')) {
            return $this->module('collections')->find($this->param('collection'), $this->param('options'));
        }

        return false;
    }

    public function collection($name = null)
    {

        if ($name && !$this->module('collections')->hasaccess($name, 'collection_edit')) {
            return $this->helper('admin')->denyRequest();
        }

        if (!$name && !$this->module('maya')->hasaccess('collections', 'create')) {
            return $this->helper('admin')->denyRequest();
        }

        $default = [
            'name' => '',
            'label' => '',
            'color' => '',
            'fields' => [],
            'acl' => new \ArrayObject,
            'sortable' => false,
            'sort' => [
                'column' => '_created',
                'dir' => -1,
            ],
            'in_menu' => false
        ];
        $defaultPHP = "<?php\n\n";

        $collection = $default;

        if ($name) {

            $collection = $this->module('collections')->collection($name);

            if (!$collection) {
                return false;
            }

            if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($collection['_id'], $meta)) {
                return $this->render('maya:views/base/locked.php', compact('meta'));
            }

            $this->app->helper('admin')->lockResourceId($collection['_id']);

            $collection = array_merge($default, $collection);
        }

        // get field templates
        $templates = [];

        foreach ($this->app->helper('fs')->ls('*.php', 'collections:fields-templates') as $file) {
            $templates[] = include($file->getRealPath());
        }

        foreach ($this->app->module('collections')->collections() as $col) {
            $templates[] = $col;
        }

        // acl groups
        $aclgroups = [];

        foreach ($this->app->helper('acl')->getGroups() as $group => $superAdmin) {

            if (!$superAdmin)
                $aclgroups[] = $group;
        }


        // rules
        $rules = [
            'create' => !$name || !$this->app->path("#storage:collections/rules/{$name}.create.php") ? $defaultPHP : $this->app->helper('fs')->read("#storage:collections/rules/{$name}.create.php"),
            'read' => !$name || !$this->app->path("#storage:collections/rules/{$name}.read.php") ? $defaultPHP : $this->app->helper('fs')->read("#storage:collections/rules/{$name}.read.php"),
            'update' => !$name || !$this->app->path("#storage:collections/rules/{$name}.update.php") ? $defaultPHP : $this->app->helper('fs')->read("#storage:collections/rules/{$name}.update.php"),
            'delete' => !$name || !$this->app->path("#storage:collections/rules/{$name}.delete.php") ? $defaultPHP : $this->app->helper('fs')->read("#storage:collections/rules/{$name}.delete.php"),
        ];

        // views
        $defaultItem = <<<HTML
        <div class="collection-grid-avatar-container uk-margin-top">
            <div class="collection-grid-avatar">
                <cp-account account="{entry._mby || entry._by}" label="{false}" size="40" if="{entry._mby || entry._by}"></cp-account>
                <cp-gravatar alt="?" size="40" if="{!(entry._mby || entry._by)}"></cp-gravatar>
            </div>
        </div>
        <div class="uk-flex uk-flex-middle uk-margin-small-top">

            <div class="uk-flex-item-1 uk-margin-small-right uk-text-small">
                <span class="uk-text-success uk-margin-small-right">{ App.Utils.dateformat( new Date( 1000 * entry._created )) }</span>
                <span class="uk-text-primary">{ App.Utils.dateformat( new Date( 1000 * entry._modified )) }</span>
            </div>

        </div>

        <div class="uk-margin-top uk-scrollable-box">
            <div class="uk-margin-small-bottom" each="{field,idy in parent.fields}" if="{ field.name != '_modified' && field.name != '_created' }">
                <span class="uk-text-small uk-text-uppercase uk-text-muted">{ field.label || field.name }</span>
                <a class="uk-link-muted uk-text-small uk-display-block uk-text-truncate" href="@route('/collections/entry/'.\$collection['name'])/{ parent.entry._id }">
                    <raw content="{ App.Utils.renderValue(field.type, parent.entry[field.name], field, lang) }" if="{parent.entry[field.name] !== undefined}"></raw>
                    <span class="uk-icon-eye-slash uk-text-muted" if="{parent.entry[field.name] === undefined}"></span>
                </a>
            </div>
        </div>
        HTML;
        $views = [
            'item' => !$name || !$this->app->path("#storage:collections/views/{$name}.item.php") ? $defaultItem : $this->app->helper('fs')->read("#storage:collections/views/{$name}.item.php"),
            'bootstrap' => !$name || !$this->app->path("#storage:collections/views/{$name}.bootstrap.php") ? $defaultPHP : $this->app->helper('fs')->read("#storage:collections/views/{$name}.bootstrap.php"),
        ];

        return $this->render('collections:views/collection.php', compact('collection', 'templates', 'aclgroups', 'rules', 'views'));
    }

    public function save_collection()
    {

        $collection = $this->param('collection');
        $rules = $this->param('rules', null);
        $views = $this->param('views', null);

        if (!$collection) {
            return false;
        }

        $isUpdate = isset($collection['_id']);

        if (!$isUpdate && !$this->module('maya')->hasaccess('collections', 'create')) {
            return $this->helper('admin')->denyRequest();
        }

        if ($isUpdate && !$this->module('collections')->hasaccess($collection['name'], 'collection_edit')) {
            return $this->helper('admin')->denyRequest();
        }

        if ($isUpdate && !$this->app->helper('admin')->isResourceEditableByCurrentUser($collection['_id'])) {
            $this->stop(['error' => "Saving failed! Collection is locked!"], 412);
        }

        $collection = $this->module('collections')->saveCollection($collection['name'], $collection, $rules, $views);

        if (!$isUpdate) {
            $this->app->helper('admin')->lockResourceId($collection['_id']);
        }

        return $collection;
    }

    public function entries($collection)
    {

        if (!$this->module('collections')->hasaccess($collection, 'entries_view')) {
            return $this->helper('admin')->denyRequest();
        }

        $collection = $this->module('collections')->collection($collection);

        if (!$collection) {
            return false;
        }

        $collection = array_merge(
            [
                'sortable' => false,
                'sort' => [
                    'column' => '_created',
                    'dir' => -1,
                ],
                'color' => '',
                'icon' => '',
                'description' => ''
            ],
            $collection
        );

        $context = _check_collection_rule($collection, 'read', ['options' => ['filter' => []]]);

        $this->app->helper('admin')->favicon = [
            'path' => 'collections:icon.svg',
            'color' => $collection['color']
        ];

        if ($context && isset($context->options['fields'])) {
            foreach ($collection['fields'] as &$field) {
                if (isset($context->options['fields'][$field['name']]) && !$context->options['fields'][$field['name']]) {
                    $field['lst'] = false;
                }
            }
        }

        $view = 'collections:views/entries.php';

        if ($override = $this->app->path('#config:collections/' . $collection['name'] . '/views/entries.php')) {
            $view = $override;
        }

        return $this->render($view, compact('collection'));
    }

    public function entry($collection, $id = null)
    {

        if ($id && !$this->module('collections')->hasaccess($collection, 'entries_view')) {
            return $this->helper('admin')->denyRequest();
        }

        if (!$id && !$this->module('collections')->hasaccess($collection, 'entries_create')) {
            return $this->helper('admin')->denyRequest();
        }

        $collection = $this->module('collections')->collection($collection);
        $entry = new \ArrayObject([]);
        $excludeFields = [];

        if (!$collection) {
            return false;
        }

        $collection = array_merge(
            [
                'sortable' => false,
                'sort' => [
                    'column' => '_created',
                    'dir' => -1,
                ],
                'color' => '',
                'icon' => '',
                'description' => ''
            ],
            $collection
        );

        $this->app->helper('admin')->favicon = [
            'path' => 'collections:icon.svg',
            'color' => $collection['color']
        ];

        if ($id) {

            $entry = $this->module('collections')->findOne($collection['name'], ['_id' => $id]);
            //$entry = $this->app->storage->findOne("collections/{$collection['_id']}", ['_id' => $id]);

            if (!$entry) {
                return maya()->helper('admin')->denyRequest();
            }

            if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($id, $meta)) {
                return $this->render('collections:views/locked.php', compact('meta', 'collection', 'entry'));
            }

            $this->app->helper('admin')->lockResourceId($id);
        }

        $context = _check_collection_rule($collection, 'read', ['options' => ['filter' => []]]);

        if ($context && isset($context->options['fields'])) {
            foreach ($context->options['fields'] as $field => $include) {
                if (!$include)
                    $excludeFields[] = $field;
            }
        }

        $view = 'collections:views/entry.php';

        if ($override = $this->app->path('#config:collections/' . $collection['name'] . '/views/entry.php')) {
            $view = $override;
        }

        return $this->render($view, compact('collection', 'entry', 'excludeFields'));
    }

    public function save_entry($collection)
    {

        $collection = $this->module('collections')->collection($collection);

        if (!$collection) {
            return false;
        }

        $entry = $this->param('entry', false);

        if (!$entry) {
            return false;
        }

        if (!isset($entry['_id']) && !$this->module('collections')->hasaccess($collection['name'], 'entries_create')) {
            return $this->helper('admin')->denyRequest();
        }

        if (isset($entry['_id']) && !$this->module('collections')->hasaccess($collection['name'], 'entries_edit')) {
            return $this->helper('admin')->denyRequest();
        }

        $entry['_mby'] = $this->module('maya')->getUser('_id');

        if (isset($entry['_id'])) {

            if (!$this->app->helper('admin')->isResourceEditableByCurrentUser($entry['_id'])) {
                $this->stop(['error' => "Saving failed! Entry is locked!"], 412);
            }

            $_entry = $this->module('collections')->findOne($collection['name'], ['_id' => $entry['_id']]);
            $revision = !(json_encode($_entry) == json_encode($entry));

        } else {

            $entry['_by'] = $entry['_mby'];
            $revision = true;

            if ($collection['sortable']) {
                $entry['_o'] = $this->app->storage->count("collections/{$collection['_id']}", ['_pid' => ['$exists' => false]]);
            }

        }

        try {
            $entry = $this->module('collections')->save($collection['name'], $entry, ['revision' => $revision]);

        } catch (\Throwable $e) {

            $this->app->stop(['error' => $e->getMessage()], 412);
        }
        $this->app->helper('admin')->lockResourceId($entry['_id']);

        return $entry;
    }

    public function delete_entries($collection)
    {

        \session_write_close();

        $collection = $this->module('collections')->collection($collection);

        if (!$collection) {
            return false;
        }

        if (!$this->module('collections')->hasaccess($collection['name'], 'entries_delete')) {
            return $this->helper('admin')->denyRequest();
        }

        $filter = $this->param('filter', false);

        if (!$filter) {
            return false;
        }

        $items = $this->module('collections')->find($collection['name'], ['filter' => $filter]);

        if (count($items)) {

            $trashItems = [];
            $time = time();
            $by = $this->module('maya')->getUser('_id');

            foreach ($items as $item) {

                $trashItems[] = [
                    'collection' => $collection['name'],
                    'data' => $item,
                    '_by' => $by,
                    '_created' => $time
                ];
            }

            $this->app->storage->getCollection('collections/_trash')->insertMany($trashItems);
        }

        $this->module('collections')->remove($collection['name'], $filter);

        return true;
    }

    public function update_order($collection)
    {

        \session_write_close();

        $collection = $this->module('collections')->collection($collection);
        $entries = $this->param('entries');

        if (!$collection)
            return false;
        if (!$entries)
            return false;

        $_collectionId = $collection['_id'];

        if (is_array($entries) && count($entries)) {

            foreach ($entries as $entry) {
                $this->app->storage->save("collections/{$_collectionId}", $entry);
            }
        }

        $this->app->trigger("collections.reorder", [$collection['name'], $entries]);
        $this->app->trigger("collections.reorder.{$collection['name']}", [$collection['name'], $entries]);

        return $entries;
    }

    public function export($collection = null, $type = 'json')
    {

        if (!$this->app->module('collections')->hasaccess($collection, 'entries_view')) {
            return $this->helper('admin')->denyRequest();
            ;
        }

        $collection = $collection ? $collection : $this->app->param('collection', '');
        $options = $this->app->param('options', []);
        $type = $this->app->param('type', $type);

        $collection = $this->module('collections')->collection($collection);

        if (!$collection)
            return false;

        $this->app->trigger('collections.export.before', [$collection, &$type, &$options]);

        switch ($type) {
            case 'json':
                return $this->json($collection, $options);
                break;
            case 'csv':
                return $this->sheet($collection, $options, 'Csv');
                break;
            case 'ods':
                return $this->sheet($collection, $options, 'Ods');
                break;
            case 'xls':
                return $this->sheet($collection, $options, 'Xls');
                break;
            case 'xlsx':
                return $this->sheet($collection, $options, 'Xlsx');
                break;
            default:
                return false;
        }

    }

    protected function json($collection, $options)
    {

        $entries = $this->module('collections')->find($collection['name'], $options);

        $this->app->response->mime = 'json';

        return \json_encode($entries, JSON_PRETTY_PRINT);

    } // end of json()
    protected function setValue(&$entry, $field) {
        if ($field['type'] == "repeater" && isset($entry[$field['name']])) {
            $entriesValues = [];
            foreach ($entry[$field['name']] as $key => $subEntry) {
                $subEntry["field"]["name"] = "value";
                $this->setValue($subEntry, $subEntry["field"]);
                $entriesValues[$key] = $subEntry["value"];
            }
            $entry[$field['name']] = implode("\r\n\r\n", $entriesValues);
        } else if ($field['type'] == "set" && isset($entry[$field['name']], $field['options'], $field['options']["fields"])) {
            try {
                //code...
                $subFields = $field['options']["fields"];
                $entriesValues = [];
                foreach ($subFields as $key => $subField) {
                    if (isset($entry[$field['name']][$subField["name"]])) {
                        $this->setValue($entry[$field['name']], $subField);
                        $entriesValues[isset($subField['label']) && !empty(($subField['label'])) ? $subField['label'] : $subField['name']] = $entry[$field['name']][$subField["name"]];
                    }
                }
                $entry[$field['name']] = implode(
                    "\r\n",
                    array_map(
                        function ($k, $v) {
                            return strtoupper($k) . " : " . $v;
                        },
                        array_keys($entriesValues),
                        array_values($entriesValues)
                    )
                );
            } catch (\Throwable $th) {
                //throw $th;
                $entry[$field['name']] = "ERROR " . $th;
            }
        } else if ($field['type'] == "image" && isset($entry[$field['name']]) && is_array($entry[$field['name']]) && isset($entry[$field['name']]["path"])) {
            $entry[$field['name']] = $this->routeFullUrl($entry[$field['name']]["path"]);
        } else if (
               in_array($field['type'], ["collectionlink", "collectionlinkselect"]) 
            && isset($entry[$field['name']], $entry[$field['name']]["display"]) 
            && is_array($entry[$field['name']]) 
        ) {
            $entry[$field['name']] = $entry[$field['name']]["display"];
        } else if (isset($entry[$field['name']], $entry[$field['name']]["display"]) && is_array($entry[$field['name']]) && is_scalar($entry[$field['name']]["display"])) {
            $entry[$field['name']] = $entry[$field['name']]["display"];
        } else if (isset($entry[$field['name']]) && is_array($entry[$field['name']])) {
            $entry[$field['name']] = json_encode($entry[$field['name']]);
        }
    }
    protected function sheet($collection = [], $_options = [], $type = 'Ods')
    {

        $user = $this->app->module('maya')->getUser();

        $filename = $collection['name'];

        $description = "Exported Maya Collection";


        if (!empty($collection['description']))
            $description .= "\r\n\r\n" . $collection['description'];

        if (!empty($_options))
            $description .= "\r\n\r\nUser defined filter options:\r\n";

        foreach ($_options as $key => $val) {
            $description .= $key . ': ' . json_encode($val) . "\r\n";
        }

        $opts = [
            'title' => !empty($collection['label']) ? $collection['label'] : $collection['name'],
            'creator' => !empty($user['name']) ? $user['name'] : $user['user'],
            'description' => trim($description),
        ];
        $spreadsheet = new \SheetExport($opts);
        $groups = isset($collection["groups"]) && !empty($collection["groups"]) ? $collection["groups"] : [];
        $groups = array_filter(
            array_map(function($group){
                return isset($group["value"], $group["value"]["name"]) ? $group["value"] : (
                    isset($group["name"]) ? $group : []
                );
            },$groups), function ($group) {
                return isset($group['enabled']) && $group['enabled'];
            }
        );
        if(empty($groups)){
            $groups = array(
                array(
                    'name' => $this("i18n")->get("Entries"),
                    'filter' =>array(),
                    'enabled' => true,
                    'sheetPrefix' => ""
                ),
            );
        }
        if (!function_exists('str_contains')) {
            function str_contains(string $haystack, string $needle)
            {
                return empty($needle) || strpos($haystack, $needle) !== false;
            }
        }
        function clone_array($arr) {
            $clone = array();
            foreach($arr as $k => $v) {
                if(is_array($v)) $clone[$k] = clone_array($v); //If a subarray
                else if(is_object($v)) $clone[$k] = clone $v; //If an object
                else $clone[$k] = $v; //Other primitive types.
            }
            return $clone;
        }
        // build sheets from group
        foreach ($groups as $groupIndex => $group) {
            try{
                // set sheet title
                try {
                    $sheet = $spreadsheet->spreadsheet->getSheet($groupIndex);
                } catch (\Throwable $th) {
                    try {
                        //code...
                        $sheet = $spreadsheet->spreadsheet->createSheet($groupIndex);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    //throw $th;
                }
            }catch (\Throwable $th) {

            }
            $spreadsheet->spreadsheet->setActiveSheetIndex($groupIndex);
            $sheet->setTitle((isset($group["sheetPrefix"]) ? $group["sheetPrefix"] : ($groupIndex.". ")).$group["name"]);
            $options = clone_array($_options);
            $collectionFields = clone_array($collection['fields']);
            // quick fix to enable _id and other meta fields
            $meta = $options['meta'] ?? null;
            if (!$meta)
                $meta = ['_id'];
            if ($meta === true || $meta === 1 || $meta === '1') {
                $meta = [
                    '_id',
                    '_created',
                    '_modified',
                    '_mby',
                    '_by',
                ];
            }
            $options["filter"] = isset($options["filter"]) ? [
                "\$and" => [
                    $options["filter"],
                    $group["filter"],
                ]
            ] : $group["filter"];
            if(empty($options["filter"]) || (isset($options["filter"]['$and'],$options["filter"]['$and'][0], $options["filter"]['$and'][1]) && empty($options["filter"]['$and'][0]) && empty($options["filter"]['$and'][1])) ){
                unset($options["filter"]);
            }else if((isset($options["filter"]['$and'],$options["filter"]['$and'][0], $options["filter"]['$and'][1]) && empty($options["filter"]['$and'][0]) && !empty($options["filter"]['$and'][1])) ){
                $options["filter"] = $options["filter"]['$and'][1];
            }else if((isset($options["filter"]['$and'],$options["filter"]['$and'][0], $options["filter"]['$and'][1]) && !empty($options["filter"]['$and'][0]) && empty($options["filter"]['$and'][1])) ){
                $options["filter"] = $options["filter"]['$and'][0];
            }
            foreach ($meta as $metaField) {
                $collectionFields[] = ['name' => $metaField];
            }

            // table headers
            $c = 'A';
            $r = '1';
            foreach ($collectionFields as $field) {

                if (empty($options['fields']) ||
                    !$this->module('collections')
                        ->is_filtered_out(
                            $field['name'], $options['fields'],
                            '_id'
                        )
                ) {
                    // $spreadsheet->setCellValue($c.$r, $field['name']);
                    $spreadsheet->setCellValue($c . $r, strtoupper(isset($field['label']) && !empty(($field['label'])) ? $field['label'] : $field['name']));
                    $c++;
                }
            }

            // table contents
            $entries = $this->module('collections')->find($collection['name'], $options);

            $c = 'A';
            $r = '2';
            
            foreach ($entries as $entry) {

                foreach ($collectionFields as $field) {

                    $this->setValue($entry, $field);

                    if (empty($options['fields']) ||
                        !$this->module('collections')
                            ->is_filtered_out(
                                $field['name'], $options['fields'],
                                '_id'
                            )
                    ) {
                        $spreadsheet->setCellValue($c . $r, $entry[$field['name']] ?? '');
                        $c++;
                    }
                }
                $c = 'A';
                $r++;
            }
        }
        // write file and exit
        $spreadsheet->write($type, $filename);

    } // end of sheet()



    public function tree()
    {

        \session_write_close();

        $collection = $this->app->param('collection');

        if (!$collection)
            return false;

        $items = $this->app->module('collections')->find($collection);

        if (count($items)) {

            $items = $this->helper('utils')->buildTree(
                $items,
                [
                    'parent_id_column_name' => '_pid',
                    'children_key_name' => 'children',
                    'id_column_name' => '_id',
                    'sort_column_name' => '_o'
                ]
            );
        }

        return $items;
    }

    public function find()
    {

        \session_write_close();

        $collection = $this->app->param('collection');
        $options = $this->app->param('options');

        if (!$collection)
            return false;

        $collection = $this->app->module('collections')->collection($collection);
        if (isset($options['filter']) && is_string($options['filter'])) {

            $filter = null;

            if (\preg_match('/^\{(.*)\}$/', $options['filter'])) {

                try {
                    $filter = json5_decode($options['filter'], true);
                } catch (\Exception $e) {
                }
            }

            if (!$filter) {
                $filter = $this->_filter($options['filter'], $collection, $options['lang'] ?? null);
            }

            $options['filter'] = $filter;
        }

        if (isset($options['search']) && is_string($options['search'])) {

            $filter = null;

            if (\preg_match('/^\{(.*)\}$/', $options['search'])) {

                try {
                    $filter = json5_decode($options['search'], true);
                } catch (\Exception $e) {
                }
            }

            if (!$filter) {
                $filter = $this->_filter($options['search'], $collection, $options['lang'] ?? null);
            }
            if (isset($options['filter']) && is_array($options['filter'])) {
                $options['filter'] = [
                    '$and' => [
                        $filter,
                        $options['filter']
                    ]
                ];
            } else {
                $options['filter'] = $filter;
            }
        }

        $this->app->trigger("collections.admin.find.before.{$collection['name']}", [&$options]);
        $entries = $this->app->module('collections')->find($collection['name'], $options);
        $this->app->trigger("collections.admin.find.after.{$collection['name']}", [&$entries, $options]);

        $count = $this->app->module('collections')->count($collection['name'], isset($options['filter']) ? $options['filter'] : []);
        $pages = isset($options['limit']) ? ceil($count / $options['limit']) : 1;
        $page = 1;

        if ($pages > 1 && isset($options['skip'])) {
            $page = ceil($options['skip'] / $options['limit']) + 1;
        }

        return compact('entries', 'count', 'pages', 'page');
    }


    public function revisions($collection, $id)
    {

        if (!$this->module('collections')->hasaccess($collection, 'entries_edit')) {
            return $this->helper('admin')->denyRequest();
        }

        $collection = $this->module('collections')->collection($collection);

        if (!$collection) {
            return false;
        }

        $entry = $this->module('collections')->findOne($collection['name'], ['_id' => $id]);

        if (!$entry) {
            return false;
        }

        $user = $this->app->module('maya')->getUser();
        $languages = $this->app->retrieve('config/languages', []);

        $allowedFields = [];

        foreach ($collection['fields'] as $field) {

            if (isset($field['acl']) && is_array($field['acl']) && count($field['acl'])) {

                if (!(in_array($user['group'], $field['acl']) || in_array($user['_id'], $field['acl']))) {
                    continue;
                }
            }

            $allowedFields[] = $field['name'];

            if (isset($field['localize']) && $field['localize']) {
                foreach ($languages as $key => $val) {
                    if (is_numeric($key))
                        $key = $val;
                    $allowedFields[] = "{$field['name']}_{$key}";
                }
            }
        }

        $revisions = $this->app->helper('revisions')->getList($id);

        return $this->render('collections:views/revisions.php', compact('collection', 'entry', 'revisions', 'allowedFields'));
    }

    protected function _filter($filter, $collection, $lang = null)
    {

        $isMongoLite = ($this->app->storage->type == 'mongolite');

        $allowedtypes = ['text', 'longtext', 'boolean', 'select', 'html', 'wysiwyg', 'markdown', 'code'];
        $criterias = [];
        $_filter = null;

        $this->app->trigger('collections.admin._filter.before', [$collection, &$filter, &$allowedtypes, &$criterias]);

        foreach ($collection['fields'] as $field) {

            $name = $field['name'];

            if ($lang && $field['localize']) {
                $name = "{$name}_{$lang}";
            }

            if ($field['type'] != 'boolean' && in_array($field['type'], $allowedtypes)) {

                $criteria = [];
                $criteria[$name] = ['$regex' => $filter];

                if (!$isMongoLite) {
                    $criteria[$name]['$options'] = 'i';
                }

                $criterias[] = $criteria;
            }

            if ($field['type'] == 'collectionlink' || $field['type'] == 'collectionlinkselect') {

                $criteria = [];
                $criteria[$name . '.display'] = ['$regex' => $filter];

                if (!$isMongoLite) {
                    $criteria[$name . '.display']['$options'] = 'i';
                }

                $criterias[] = $criteria;
            }

            if ($field['type'] == 'location') {

                $criteria = [];
                $criteria[$name . '.address'] = ['$regex' => $filter];

                if (!$isMongoLite) {
                    $criteria[$name . '.address']['$options'] = 'i';
                }

                $criterias[] = $criteria;
            }

            $this->app->trigger('collections.admin._filter.field', [$collection, $name, $field, $filter, &$criterias]);
        }

        if (count($criterias)) {
            $_filter = ['$or' => $criterias];
        }

        return $_filter;
    }
}