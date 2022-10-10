<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
// ACL
$app('acl')->addResource("editor", ['create', 'delete', 'manage']);

// ADMIN
if (MAYA_ADMIN_CP) {
    include_once(__DIR__.'/admin.php');
}