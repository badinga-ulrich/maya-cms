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

$accounts = $app->storage->find('maya/accounts')->toArray();


if (count($accounts)) {

    CLI::writeln("Exporting maya/accounts (".count($accounts).")");

    $app->helper('fs')->write("{$target}/maya/accounts.json", json_encode($accounts, JSON_PRETTY_PRINT));
}
