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

CLI::writeln('Flushing singletons data');


foreach ($app->module('singletons')->singletons() as $name => $singleton) {

    $app->storage->removeKey('singletons', $name);
}
