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

class Settings extends \Maya\AuthController {


    public function index() {
        return $this->render('maya:views/settings/index.php');
    }

    public function info() {

        if (!$this->module('maya')->hasaccess('maya', 'info')) {
            return $this->helper('admin')->denyRequest();
        }

        $info                  = [];

        $info['app']           = $this->app->helper('admin')->data['maya'];

        $info['system']        = php_uname();
        $info['phpversion']    = phpversion();
        $info['sapi_name']     = php_sapi_name();
        $info['extensions']    = get_loaded_extensions();
        $info['mailer']        = $this->app->retrieve('config/mailer', false);

        $info['jobs_queue'] = [
            'running' => $this->app->helper('jobs')->isRunnerActive(),
            'cntjobs' => $this->app->helper('jobs')->countJobs()
        ];

        $update = $this->getUptdateInfo();

        return $this->render('maya:views/settings/info.php', compact('info', 'update'));
    }

    public function edit($createconfig = false) {

        if (!$this->module('maya')->isSuperAdmin()) {
            return false;
        }

        if ($createconfig && !$this->app->path(MAYA_CONFIG_PATH)) {

            if ($this->app->helper('fs')->mkdir(dirname(MAYA_CONFIG_PATH))) {
                $this->app->helper('fs')->write(MAYA_CONFIG_PATH, "# Maya settings\n");
            }
        }

        $configexists = $this->app->path(MAYA_CONFIG_PATH);

        return $this->render('maya:views/settings/edit.php', compact('configexists'));
    }

    public function update() {

        if (!$this->module('maya')->isSuperAdmin()) {
            return false;
        }

        $update = $this->getUptdateInfo();

        $this->app->trigger('maya.update.before', [$update]);
        $ret = $this->app->helper('updater')->update($update['zipfile'], $update['target'], $update['options']);
        $this->app->trigger('maya.update.after', [$update]);

        return $ret;
    }

    protected function getUptdateInfo() {

        $update = new \ArrayObject(array_merge([
            'package.json' => 'https://raw.githubusercontent.com/badinga-ulrich/maya-cms/master/package.json',
            'zipfile' => 'https://github.com/badinga-ulrich/maya-cms/archive/master.zip',
            'target'  => MAYA_DIR,
            'options' => ['zipRoot' => 'maya-master']
        ], $this->app->retrieve('config/update', [])));

        return $update;
    }
}
