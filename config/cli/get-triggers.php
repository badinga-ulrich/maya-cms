<?php

/*
  Find all triggers in maya
  
  call it through cli with `./cp get-triggers`
  
  The script creates a file triggers.php with all triggers. Call it
  afterwards with `./cp triggers` to print it's content
  
  Optional parameters:
  
  `--print triggers`  --> prints only triggers
  `--print all`       --> prints everything
  
  default: no output in cli, it just creates /config/cli/triggers.php
*/

if (!MAYA_CLI) return;

$print     = $app->param('print', null);

// settings
$extensions = ['php', 'md', 'html', 'js', 'tag'];
$strings    = [];
$dirs       = [MAYA_DIR.'/core', MAYA_DIR.'/lib/Lime'];
$files      = [MAYA_DIR.'/index.php', MAYA_DIR.'/bootstrap.php'];

$triggers = [];

// iterate function
function iterate($iterator) {
    
    global $extensions;
    global $triggers;
    
    foreach ($iterator as $file) {

        if (in_array($file->getExtension(), $extensions)) {

            $contents = file_get_contents($file->getRealPath());
            
            preg_match_all('/(?:trigger)\((["\'])(.*?)(["\'])(,\s*(.*?))?\)/', $contents, $matches);

            if (!isset($matches[2])) continue;

            $i = 0;
            foreach ($matches[2] as $string) {
                
                if ( isset($matches[5][$i]) && !( isset($triggers[$string]['arguments']) && in_array($matches[5][$i], $triggers[$string]['arguments']) ) )
                    $triggers[$string]['arguments'][] = $matches[5][$i];
                
                if ( !( isset($triggers[$string]['files']) && in_array($file->getRealPath(), $triggers[$string]['files']) ) )
                    $triggers[$string]['files'][] = $file->getRealPath();
                
                $i++;
            }

        }
    }
    
}

// dirs
foreach ($dirs as $dir) {

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
    
    iterate($iterator);

}

// files
foreach ($files as $filename) {
    
    $filelist[] = new SPLFileObject($filename);
    
}
iterate($filelist);

if (count($triggers)) {

    ksort($triggers);
    
    $app->helper('fs')->write("#config:cli/triggers.php", '<?php var_export('.var_export(['triggers' =>array_keys($triggers), 'arguments' => $triggers], true).');');
}

CLI::writeln("Done! Trigger file created: config/cli/triggers.php", true);

if ($print == 'triggers') {
    var_export(array_keys($triggers));
}
if ($print == 'all') {
    var_export(['triggers' =>array_keys($triggers), 'arguments' => $triggers]);
}