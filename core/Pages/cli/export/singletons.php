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
    return;
}

foreach ($app->module('pages')->pages() as $name => $page) {

    $data = $app->storage->getKey('pages', $name);

    if (count($data)) {

        CLI::writeln("Exporting pages/{$name}");

        $app->helper('fs')->write("{$target}/pages/{$name}.json", json_encode($data, JSON_PRETTY_PRINT));
    }
}
