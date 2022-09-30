<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!$app->helper('jobs')->isRunnerActive()) {
    return CLI::writeln("No active job queue runner found", false);
}

$app->helper('jobs')->stopRunner();

CLI::writeln("Maya: Job queue runner stopped", false);