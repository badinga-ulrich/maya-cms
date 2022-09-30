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

$lang     = $app->param('lang', null);
$language = $app->param('language', $lang);
$author   = $app->param('author', 'Maya CLI');

if (!$lang) {
    return CLI::writeln("--lang parameter is missing", false);
}

// settings
$extensions = ['php', 'md', 'html', 'js', 'tag'];
$strings    = [];
$dirs       = [MAYA_DIR.'/modules'];

foreach ($dirs as $dir) {

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(MAYA_DIR.'/modules'), RecursiveIteratorIterator::SELF_FIRST);

    foreach ($iterator as $file) {

        if (in_array($file->getExtension(), $extensions)) {

            $contents = file_get_contents($file->getRealPath());

            preg_match_all('/(?:\@lang|App\.i18n\.get|App\.ui\.notify)\((["\'])((?:[^\1]|\\.)*?)\1(,\s*(["\'])((?:[^\4]|\\.)*?)\4)?\)/', $contents, $matches);

            if (!isset($matches[2])) continue;

            foreach ($matches[2] as &$string) {
                $strings[$string] = $string;
            }

        }
    }
}

if (count($strings)) {

    $strings['@meta'] = [
        'language' => $language,
        'author'   => $author,
        'date' => [
            'shortdays'   => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'longdays'    => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'shortmonths' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'longmonths'  => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        ]
    ];

    if ($app->path("#config:maya/i18n/{$lang}.php")) {
        $langfile = include($app->path("#config:maya/i18n/{$lang}.php"));
        $strings  = array_merge($strings, $langfile);
    }

    ksort($strings);

    $app->helper('fs')->write("#config:maya/i18n/{$lang}.php", '<?php return '.$app->helper('utils')->var_export($strings, true).';');
}

CLI::writeln("Done! Language file created: config/maya/i18n/{$lang}.php", true);
