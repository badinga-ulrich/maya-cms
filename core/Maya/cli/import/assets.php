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
$uploads = $app->path('#uploads:');
$check = $app->param('check', false);

if (!file_exists("{$src}/maya/assets.json")) {
    return;
}


if ($data = $fs->read("{$src}/maya/assets.json")) {

    if ($assets = json_decode($data, true)) {

        if (count($assets)) {

            CLI::writeln("Importing maya/assets (".count($assets).")");

            foreach ($assets as $asset) {

                $path    = trim($asset['path'], '/');
                $_src    = "{$src}/maya/assets/{$path}";
                $_target = "{$uploads}/{$path}";

                if (!file_exists($_src)) continue;

                if ($check) {

                    if (!$app->storage->count('maya/assets', ['_id' => $asset['_id']])) {
                        $app->storage->insert('maya/assets', $asset);

                        $fs->mkdir(dirname($_target));
                        $fs->copy($_src, $_target);
                    }

                } else {

                    $app->storage->insert('maya/assets', $asset);

                    $fs->mkdir(dirname($_target));
                    $fs->copy($_src, $_target);
                }
            }
        }
    }
}
