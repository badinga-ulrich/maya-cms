<?php
/**
 * File containing the class {@see \Mistralys\MarkdownViewer\DocsViewer}.
 *
 * @package MarkdownViewer
 * @see \Mistralys\MarkdownViewer\DocsViewer
 */

declare(strict_types=1);

namespace Mistralys\MarkdownViewer;

use AppUtils\OutputBuffering;use AppUtils\OutputBuffering_Exception;

/**
 * Renders the documentation viewer UI, using the
 * list of documents contained in the manager instance.
 *
 * @package MarkdownViewer
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class DocsViewer
{
    public const ERROR_NO_DOCUMENTS_AVAILABLE = 82001;

    private string $title = 'Documentation';
    private string $menuLabel = 'Available documents';
    private DocsManager $docs;
    private bool $darkMode = false;
    private string $vendorURL;
    private string $pathURL = "";
    private string $rootDir = "";
    private string $rootPath = "";
    private string $packageURL;

    /**
     * @param DocsManager $manager
     * @param string $vendorURL
     * @throws DocsException
     * @see DocsViewer::ERROR_NO_DOCUMENTS_AVAILABLE
     */
    public function __construct(DocsManager $manager, string $vendorURL)
    {
        $this->docs = $manager;
        $this->vendorURL = rtrim($vendorURL, '/');


        if(!$this->docs->hasFiles()) {
            throw new DocsException(
                'Cannot start viewer, the are no documents to display.',
                '',
                self::ERROR_NO_DOCUMENTS_AVAILABLE
            );
        }
    }

    public function setRootPath($rootPath){
        $this->rootPath = isset($rootPath) ? $rootPath : "";
        return $this;
    }

    public function setRootDir($rootDir){
        $this->rootDir = isset($rootDir) ? $rootDir : "";
        return $this;
    }

    public function setPathUrl($pathURL){
        $this->pathURL = isset($pathURL) ? $pathURL : "";
        return $this;
    }

    public function makeDarkMode() : DocsViewer
    {
        $this->darkMode = true;
        return $this;
    }

    /**
     * Sets the title of the document and the navigation label.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) : DocsViewer
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets the label of the menu item listing all the available documents.
     *
     * @param string $label
     * @return $this
     */
    public function setMenuLabel(string $label) : DocsViewer
    {
        $this->menuLabel = $label;
        return $this;
    }

    public function getActiveFileID() : string
    {
        if(isset($_REQUEST['doc']) && $this->docs->idExists($_REQUEST['doc'])) {
            return $_REQUEST['doc'];
        }

        return $this->docs->getFirstFile()->getID();
    }

    public function getActiveFile() : DocFile
    {
        return $this->docs->getByID($this->getActiveFileID());
    }

    public function display() : void
    {
        $parser = new DocParser($this->getActiveFile());

?><!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php if($this->rootDir){ ?>
        <base href="<?php echo str_replace($this->rootDir,$this->rootPath,dirname($parser->file->getPath())) ?>/">
        <?php }; ?>
        <title><?php echo $this->title ?></title>
        <style>
            
            html, body, #scaffold {
                height: 100%;
            }
            body {
                height: calc(100% - 64px);
            }

            img, pre {
                max-width: 100%;
                border-radius: 10px !important;
            }
            #sidebar {
                min-width: 16rem;
                background: #e2e2e2 !important;
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                padding-top: 64px;
                border-radius: 10px;
                margin: 16px;
                border: 5px solid #04aa6d;
            }
            #sidebar:before{
                display: flex;
                content: "Index";
                position: absolute;
                top: 0;
                background: #04aa6d;
                color : #fff;
                width: 100%;
                height: 64px;
                justify-content: center;
                font-size: 30px;
                font-weight: bold;
                align-items: center;
            }
            #sidebar .sidebar-wrapper{
                width: auto;
                position : initial;
            }
            #sidebar .sidebar-wrapper .nav-level-0{
                margin: 2rem;
            }
            #sidebar .sidebar-wrapper ul{
                list-style: none;
                color :  #414141;
            }
            #sidebar .sidebar-wrapper ul li, 
            #sidebar .sidebar-wrapper ul li a {
                color :  #414141;
                text-transform: initial;
            }
            .navbar.bg-dark {
                background-color: #e7e8ea !important;
            }
            .navbar.bg-dark .navbar-brand {
                color: #000;
            }
            .navbar-brand:hover #sidebar{
                display: block;
            }
            /* Dropdown Button */
            .dropbtn {
                background-color: #04AA6D;
                color: white;
                padding: 10px;
                font-size: 16px;
                border: none;
                border-radius: 10px;
                width: 268px;
            }

            /* The container <div> - needed to position the dropdown content */
            .dropdown {
            position: relative;
            display: inline-block;
            }

            /* Dropdown Content (Hidden by Default) */
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f1f1f1;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
                width: 268px;
            }

            /* Links inside the dropdown */
            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            /* Change color of dropdown links on hover */
            .dropdown-content a:hover {background-color: #ddd;}

            /* Show the dropdown menu on hover */
            .dropdown:hover .dropdown-content {
                display: block;
                right: 15px;
                top: 35px;
                border-radius: 10px;
                overflow: hidden;
            }

            /* Change the background color of the dropdown button when the dropdown content is shown */
            .dropdown:hover .dropbtn {
                background-color: #3e8e41;
                border-radius: 10px 10px 0 0;
            }
        </style>
    </head>
    <body>

        <nav class="navbar navbar-dark bg-dark fixed-top">
            <div class="navbar-brand" style="margin-left: 15px;user-select: none;text-transform: capitalize;">
                <?php echo $this->title ?>
                <?php echo $parser->file->getTitle() ?>
                <span id="sidebar">
                    <span class="sidebar-wrapper">
                        <?php echo $this->renderMenu($parser); ?>
                    </span>
                </span>
            </div>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <button style="margin-right: 15px;" class="dropbtn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $this->menuLabel ?>
                    </button>
                    <div class="dropdown-content">
                    <?php
                        $files = $this->docs->getFiles();
                        uasort($files, function($a, $b){
                            return strcasecmp($a->getPath(), $b->getPath());
                        });
                        foreach ($files as $file) {
                            ?>
                            <a 
                                style="text-transform: capitalize;" 
                                class="dropdown-item"  
                                
                                <?php if($parser->file->getID() != $file->getID()): ?>
                                href="<?php echo $this->pathURL; ?>?doc=<?php echo $file->getID() ?>"
                                <?php endif; ?>
                            >
                                <?php echo $file->getTitle() ?>
                            </a>
                            <?php
                        }
                    ?>
                    </div>
                </li>
                </li>
            </ul>
        </nav>
        <table id="scaffold">
            <tbody>
                <tr>
                    <td id="content">
                        <div class="content-wrapper">
                            <?php echo $parser->render(); ?>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
            if($this->darkMode) {
                ?>
                    <link rel="stylesheet" href="<?php echo $this->getPackageURL() ?>/css/slate.min.css">
                <?php
            }
            else
            {
                ?>
                    <link rel="stylesheet" href="<?php echo $this->vendorURL ?>/twbs/bootstrap/dist/css/bootstrap.min.css">
                <?php
            }
        ?>
        <link rel="stylesheet" href="<?php echo $this->getPackageURL() ?>/css/styles.css">
        <?php

            if($this->darkMode) {
                ?>
                    <link rel="stylesheet" href="<?php echo $this->getPackageURL() ?>/css/styles-dark.css">
                <?php
            }

        ?>
        <p>
        </p>
        <script src="<?php echo $this->vendorURL ?>/components/jquery/jquery.js"></script>
        <script src="<?php echo $this->vendorURL ?>/twbs/bootstrap/dist/js/bootstrap.js"></script>
    </body>
</html><?php
    }

    public function setPackageURL(string $url) : DocsViewer
    {
        $this->packageURL = rtrim($url, '/');
        return $this;
    }

    private function getPackageURL() : string
    {
        if(!empty($this->packageURL)) {
            return $this->packageURL;
        }

        return $this->vendorURL.'/mistralys/markdown-viewer';
    }

    /**
     * @param DocHeader[] $headers
     * @return string
     * @throws OutputBuffering_Exception
     */
    private function renderMenu(DocParser $parser) : string
    {
        OutputBuffering::start();

        ?>
        <ul class="nav-level-0">
            <?php
            foreach ($parser->getHeaders() as $header)
            {
                echo $header->render($this->pathURL.'?doc='.$parser->file->getID());
            }
            ?>
        </ul>
        <?php

        return OutputBuffering::get();
    }
}
