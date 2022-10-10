<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!MAYA_CLI) return;

$src = $app->param('src', null);

if (!$src) {
    return;
}

$fs = $app->helper('fs');
$pages = $fs->ls("{$src}/pages");
$check = $app->param('check', false);

if (count($pages)) {

    foreach ($pages as $__file) {

        $name = $__file->getBasename('.json');

        if ($_page = $app->module('pages')->page($name)) {

            $data = $fs->read($__file->getRealPath());

            if ($data = json_decode($data, true)) {

                CLI::writeln("Importing pages/{$name}");

                if ($check) {
                    if (!$app->storage->getKey('pages', $name)) {
                        $app->storage->setKey('pages', $name, $data);
                    }
                } else {
                    $app->storage->setKey('pages', $name, $data);
                }
            }
        }
    }
}
