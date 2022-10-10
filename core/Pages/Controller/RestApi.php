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

class RestApi extends \LimeExtra\Controller {

    public function page($name, $field=null) {

        $user = $this->module('maya')->getUser();

        if ($user) {
            $pages = $this->module('pages')->getPagesInGroup($user['group']);
        } else {
            $pages = $this->module('pages')->pages();
        }

        if (!isset($pages[$name])) {
           return $this->stop('{"error": "Page not found"}', 412);
        }
        unset($pages[$name]["html"]);
        return $field ? ($pages[$name][$field] ?? null) : $pages[$name];
    }

    public function listPages($extended = false) {

        $user = $this->module('maya')->getUser();

        if ($user) {
            $pages = $this->module('pages')->getPagesInGroup($user['group']);
        } else {
            $pages = $this->module('pages')->pages();
        }

        return $extended ? $pages : \array_keys($pages);
    }

    
    public function index($extended = false) {
        return $this->listPages($extended);
    }
}
