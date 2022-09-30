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

class Base extends \Maya\AuthController {

    public function dashboard() {

        $settings = $this->app->storage->getKey('maya/options', 'dashboard.widgets.'.$this->user["_id"], []);

        $widgets  = new \ArrayObject([]);

        $this->app->trigger('admin.dashboard.widgets', [$widgets]);

        $areas = [
            'main' => new \SplPriorityQueue(),
            'aside-left' => new \SplPriorityQueue(),
            'aside-right' => new \SplPriorityQueue()
        ];

        foreach($widgets as &$widget) {

            $name = $widget['name'];
            $area = isset($widget['area']) && in_array($widget['area'], ['main', 'aside-left', 'aside-right']) ? $widget['area'] : 'main';

            $area = \Lime\fetch_from_array($settings, "{$name}/area", $area);
            $prio = \Lime\fetch_from_array($settings, "{$name}/prio", 0);

            $areas[$area]->insert($widget, -1 * $prio);
        }

        return $this->render('maya:views/base/dashboard.php', compact('areas', 'widgets'));
    }

    public function savedashboard() {

        $widgets = $this->app->param('widgets', []);

        $this->app->storage->setKey('maya/options', 'dashboard.widgets.'.$this->user["_id"], $widgets);

        return $widgets;
    }

    public function search() {

        \session_write_close();

        $query = $this->app->param('search', false);
        $list  = new \ArrayObject([]);

        if ($query) {
            $this->app->trigger('maya.search', [$query, $list]);
        }

        return json_encode($list->getArrayCopy());
    }

    public function call($module, $method) {

        $args = (array)$this->param('args', []);
        $acl  = $this->param('acl', null);

        if (!$acl) {
            return false;
        }

        if (!$this->module('maya')->hasaccess($module, $acl)) {
            return false;
        }

        $return = call_user_func_array([$this->app->module($module), $method], $args);

        return '{"result":'.json_encode($return).'}';
    }
}
