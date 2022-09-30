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

$target = $app->param('target', null);

if (!$target) {
    return CLI::writeln("--target parameter is missing", false);
}

$options = new ArrayObject([
    'target' => $target
]);

$fs = $app->helper('fs');

foreach ($app->paths('#cli') as $__dir) {

    foreach ($fs->ls($__dir.'export') as $__file) {
        include($__file->getRealPath());
    }
}

CLI::writeln("Done exporting data.", true);

$app->trigger('maya.export', [$options]);
