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

CLI::writeln('Flushing forms data');


foreach ($app->module('forms')->forms() as $name => $form) {

    $fid = $form['_id'];
    $app->storage->dropCollection("forms/{$fid}");
}
