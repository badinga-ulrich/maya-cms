<?php
use Mistralys\MarkdownViewer\DocsViewer;
use Mistralys\MarkdownViewer\DocsManager;
require_once  __DIR__.'/vendor/autoload.php';

$app->module('documentation')->extend([
  'docViewer' => function(){
    $manager = new DocsManager();
    
    // Add all the files you wish to view here, along with
    // a title that will be shown in the UI. 
    foreach (glob(__DIR__."/docs/**/*.md") as $filename) {
      $title = preg_replace("#\/\d*_#","/",$filename);
      $title = str_replace(".md","", str_replace("-"," ", str_replace(__DIR__."/docs", "", $title)));
      $manager->addFile(implode(" » ",explode("/",$title)), $filename);
    }
    // $manager->addFolder(__DIR__."/docs/api/",false);
    // $manager->addFolder(__DIR__."/docs/api/",false);
    // $manager->addFolder(__DIR__."/docs/getting-started/",true);
    // $manager->addFolder(__DIR__."/docs/modules/",true);
    // $manager->addFolder(__DIR__."/docs/reference/",true);
    
    // The viewer needs to know the URL to the vendor/ folder, relative
    // to the script. This is needed to load the clientside dependencies,
    // like jQuery and Bootstrap.
    (new DocsViewer(
      $manager, 
      '/core/'.basename(__DIR__)."/vendor"
    ))
        ->setPathUrl("/documentation")
        ->setRootDir(__DIR__)
        ->setRootPath('/core/'.basename(__DIR__))
        ->setTitle('Documentation')
        ->display();
  }
]);



// ADMIN
if (MAYA_ADMIN && !MAYA_API_REQUEST) {
  include_once(__DIR__.'/admin.php');
}else if(!MAYA_API_REQUEST){
  $app->bind("/documentation", function(){
    maya()->module('documentation')->docViewer();
  });
}
