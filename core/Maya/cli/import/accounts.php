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

if (!file_exists("{$src}/maya/accounts.json")) {
    return;
}

$check = $app->param('check', false);

if ($data = $app->helper('fs')->read("{$src}/maya/accounts.json")) {

    if ($accounts = json_decode($data, true)) {

        if (count($accounts)) {

            CLI::writeln("Importing maya/accounts (".count($accounts).")");

            foreach ($accounts as $account) {
                if ($check) {
                    if (!$app->storage->count('maya/accounts', ['_id' => $account['_id']])) {
                        $app->storage->insert('maya/accounts', $account);
                    }
                } else {
                    $app->storage->insert('maya/accounts', $account);
                }
            }
        }
    }
}
