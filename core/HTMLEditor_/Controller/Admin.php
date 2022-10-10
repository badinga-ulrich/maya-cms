<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HTMLEditor\Controller;


class Admin extends \Maya\AuthController {

    public function index() {
        
        return $this->render('htmleditor:views/editor.php');
    }
    public function pages($id=null){
        $pages = $this->app->storage->getCollection('maya/editor')->find("pages",[
            "filter" => isset($id) ? [
                "_id" => $id
            ] :[
                "active" => true
            ]
        ])->toArray();
        return [
            "pages" => $pages,
            "count" => count($pages) 
        ];
    }

}
