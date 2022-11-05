<?php
/**
 * This file is part of the Maya project.
 *
 * (c) Ulrich Badinga - 🅱🅰🅳🅻🅴🅴, https://badinga-ulrich.github.io/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Maya start time
 */
define('MAYA_START_TIME', microtime(true));

if (!defined('MAYA_CLI')) {
    define('MAYA_CLI', PHP_SAPI == 'cli');
}

// Autoload vendor libs
include(__DIR__.'/lib/vendor/autoload.php');

// include core classes for better performance
if (!class_exists('Lime\\App')) {
    include(__DIR__.'/lib/Lime/App.php');
    include(__DIR__.'/lib/LimeExtra/App.php');
    include(__DIR__.'/lib/LimeExtra/Controller.php');
}
/**
 * Clean URL remove multiple slash
 */
$_SERVER['REQUEST_URI'] = preg_replace("#/+#","/", $_SERVER['REQUEST_URI']);
/*
 * Autoload from lib folder (PSR-0)
 */

spl_autoload_register(function($class){
    $class_path = __DIR__.'/lib/'.str_replace('\\', '/', $class).'.php';
    if(file_exists($class_path)) include_once($class_path);
});

// load .env file if exists
DotEnv::load(__DIR__);

// check for custom defines
if (file_exists(__DIR__.'/defines.php')) {
    include(__DIR__.'/defines.php');
}

/*
 * Collect needed paths
 */

$MAYA_DIR         = str_replace(DIRECTORY_SEPARATOR, '/', __DIR__);
$MAYA_DOCS_ROOT   = str_replace(DIRECTORY_SEPARATOR, '/', isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : dirname(__DIR__));

# make sure that $_SERVER['DOCUMENT_ROOT'] is set correctly
if (strpos($MAYA_DIR, $MAYA_DOCS_ROOT)!==0 && isset($_SERVER['SCRIPT_NAME'])) {
    $MAYA_DOCS_ROOT = str_replace(dirname(str_replace(DIRECTORY_SEPARATOR, '/', $_SERVER['SCRIPT_NAME'])), '', $MAYA_DIR);
}


$MAYA_BASE          = trim(str_replace($MAYA_DOCS_ROOT, '', $MAYA_DIR), "/");
$MAYA_BASE_URL      = strlen($MAYA_BASE) ? "/{$MAYA_BASE}": $MAYA_BASE;
$MAYA_BASE_ROUTE    = $MAYA_BASE_URL;
$MAYA_HOSTS_CONFIG_FILE  = $MAYA_DIR."/.htconfig";
$MAYA_HOST          = file_exists($MAYA_HOSTS_CONFIG_FILE) && is_readable($MAYA_HOSTS_CONFIG_FILE) ? @strtolower(preg_replace("/(127.0.0.\d|0.0.0.0)/","localhost", isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) ?  preg_replace("/:\d+\$/", "",$_SERVER["HTTP_HOST"]) : $_SERVER['SERVER_NAME'])) : "";
$MAYA_HOST_CONFIG  = [];
if($MAYA_HOST){
    // check
    $hosts_configs = Spyc::YAMLLoad($MAYA_HOSTS_CONFIG_FILE);
    if(isset($hosts_configs[$MAYA_HOST]) && is_array($hosts_configs[$MAYA_HOST]))
        $MAYA_HOST_CONFIG = $hosts_configs[$MAYA_HOST];
    if(isset($hosts_configs["default"]) && is_array($hosts_configs["default"])){
        $MAYA_HOST_CONFIG = array_replace_recursive($hosts_configs["default"], $MAYA_HOST_CONFIG);
    }
    if(isset($MAYA_HOST_CONFIG["isRoot"])){
        if($MAYA_HOST_CONFIG["isRoot"]){
            $MAYA_HOST = "";
        }
        unset($MAYA_HOST_CONFIG["isRoot"]);
    }
}

/*
 * SYSTEM DEFINES
 */
if (!defined('DEF_RIGHTS'))                  define('DEF_RIGHTS'          , 0700);
if (!defined('MAYA_DIR'))                    define('MAYA_DIR'            , $MAYA_DIR);
if (!defined('MAYA_ENV_ROOT'))               define('MAYA_ENV_ROOT'       , MAYA_DIR);
if (!defined('MAYA_STORAGE_DIR'))            define('MAYA_STORAGE_DIR'    , MAYA_ENV_ROOT.'/storage');
if (!defined('MAYA_ADMIN'))                  define('MAYA_ADMIN'          , 0);
if (!defined('MAYA_HOST'))                   define('MAYA_HOST'           , $MAYA_HOST ? $MAYA_HOST : "");
if (!defined('MAYA_HOST_CONFIG'))            define('MAYA_HOST_CONFIG'    , $MAYA_HOST_CONFIG);
if (!defined('MAYA_HOST_FOLDER'))            define('MAYA_HOST_FOLDER'    , MAYA_STORAGE_DIR.($MAYA_HOST ? ("/".MAYA_HOST) : ""));
if (!defined('MAYA_DOCS_ROOT'))              define('MAYA_DOCS_ROOT'      , $MAYA_DOCS_ROOT);
if (!defined('MAYA_BASE_URL'))               define('MAYA_BASE_URL'       , $MAYA_BASE_URL);
if (!defined('MAYA_API_REQUEST'))            define('MAYA_API_REQUEST'    , MAYA_ADMIN && strpos($_SERVER['REQUEST_URI'], MAYA_BASE_URL.'/api/')!==false ? 1:0);
if (!defined('MAYA_SITE_DIR'))               define('MAYA_SITE_DIR'       , MAYA_ENV_ROOT == MAYA_DIR ?  ($MAYA_DIR == MAYA_DOCS_ROOT ? MAYA_DIR : dirname(MAYA_DIR)) :  MAYA_ENV_ROOT);
if (!defined('MAYA_BASE_ROUTE'))             define('MAYA_BASE_ROUTE'     , $MAYA_BASE_ROUTE);
if (!defined('MAYA_CONFIG_DIR'))             define('MAYA_CONFIG_DIR'     , (MAYA_HOST ? MAYA_HOST_FOLDER : MAYA_ENV_ROOT).'/config');
if (!defined('MAYA_STORAGE_FOLDER'))         define('MAYA_STORAGE_FOLDER' , MAYA_HOST_FOLDER);
if (!defined('MAYA_PUBLIC_STORAGE_FOLDER'))  define('MAYA_PUBLIC_STORAGE_FOLDER' , MAYA_HOST_FOLDER);
if (!defined('MAYA_ADMIN_CP'))               define('MAYA_ADMIN_CP'       , MAYA_ADMIN && !MAYA_API_REQUEST ? 1 : 0);

if (!file_exists(dirname(MAYA_HOST_FOLDER))){
    if(!mkdir(dirname(MAYA_HOST_FOLDER), DEF_RIGHTS)) {
        die('Failed to create host...');
    }
    chmod(dirname(MAYA_HOST_FOLDER),DEF_RIGHTS);
}

if (!file_exists(MAYA_HOST_FOLDER)){
    if(!mkdir(MAYA_HOST_FOLDER, DEF_RIGHTS)) {
        die('Failed to create host...');
    }
    chmod(MAYA_HOST_FOLDER,DEF_RIGHTS);
}

if (!defined('MAYA_CONFIG_PATH')) {
    $_configpath = MAYA_CONFIG_DIR.'/config.'.(file_exists(MAYA_CONFIG_DIR.'/config.php') ? 'php':'yaml');
    if (!file_exists(dirname($_configpath)))
        if(!mkdir(dirname($_configpath), DEF_RIGHTS, true)) {
            die('Failed to create config...');
        }else{
            $config = <<<YAML
# Maya settings
app.name: Maya CMS
i18n: fr
languages:
    default: Francais
    en: English
YAML;
            file_put_contents($_configpath, $config);
            chmod($_configpath, DEF_RIGHTS);
        }
    define('MAYA_CONFIG_PATH', $_configpath);
}

function maya($module = null) {
    static $app;

    if (!$app) {
        $secKey =  uniqid();
        $secKeyFile =  MAYA_STORAGE_FOLDER."/sec-key";
        if(is_file($secKeyFile) && is_readable($secKeyFile)){
            $secKey = file_get_contents($secKeyFile);
        }else if(is_writable(MAYA_STORAGE_FOLDER) && is_file($secKey) ){
            file_put_contents($secKeyFile, $secKey);
        }else{
            $secKey = 'c3b40c4c-db44-s5h7-a814-b4931a15e5e1';
        };
        $customConfig = [];

        // load custom config
        if (file_exists(MAYA_CONFIG_PATH)) {
            $customConfig = preg_match('/\.yaml$/', MAYA_CONFIG_PATH) ? Spyc::YAMLLoad(MAYA_CONFIG_PATH) : include(MAYA_CONFIG_PATH);
        }
        // load config
        $config = array_replace_recursive([

            'debug'        => preg_match('/(127.0.0.1|0.0.0.0|localhost|::1|\.local)$/', @$_SERVER['SERVER_NAME']),
            'app.name'     => 'Maya CMS',
            'base_url'     => MAYA_BASE_URL,
            'base_route'   => MAYA_BASE_ROUTE,
            'docs_root'    => MAYA_DOCS_ROOT,
            'session.name' => md5(MAYA_ENV_ROOT),
            'session.init' => (MAYA_ADMIN && !MAYA_API_REQUEST) ? true : false,
            'sec-key'      => $secKey,
            'i18n'         => 'en',
            'database'     => ['server' => 'mongolite://'.(MAYA_STORAGE_FOLDER.'/data'), 'options' => ['db' => 'database'], 'driverOptions' => [] ],
            'memory'       => ['server' => 'mongolite://'.(MAYA_STORAGE_FOLDER.'/data'), 'options' => ['db'=>'memory'], 'driverOptions' => [] ],
            // 'memory'       => ['server' => 'redislite://'.(MAYA_STORAGE_FOLDER.'/data/maya.memory.sqlite'), 'options' => [] ],

            'paths'         => [
                '#root'     => MAYA_DIR,
                '#storage'  => MAYA_STORAGE_FOLDER,
                '#pstorage' => MAYA_PUBLIC_STORAGE_FOLDER,
                '#data'     => MAYA_STORAGE_FOLDER.'/data',
                '#cache'    => MAYA_STORAGE_FOLDER.'/cache',
                '#tmp'      => MAYA_STORAGE_FOLDER.'/tmp',
                '#thumbs'   => MAYA_PUBLIC_STORAGE_FOLDER.'/thumbs',
                '#uploads'  => MAYA_PUBLIC_STORAGE_FOLDER.'/uploads',
                '#addons'   => (MAYA_HOST ? MAYA_STORAGE_FOLDER : MAYA_ENV_ROOT).'/addons',
                '#public'   => (MAYA_HOST ? MAYA_STORAGE_FOLDER : MAYA_ENV_ROOT).'/public',
                '#modules'  => MAYA_DIR.'/modules',
                '#config'   => MAYA_CONFIG_DIR,
                'assets'    => MAYA_DIR.'/assets',
                'site'      => MAYA_SITE_DIR
            ],

            'filestorage' => [],

        ], array_replace_recursive(is_array($customConfig) ? $customConfig : [], MAYA_HOST_CONFIG));
        foreach ($config["paths"] as $key => $path) {
            if(preg_match("#^".preg_quote(MAYA_STORAGE_FOLDER)."#",$path)){
                if (!file_exists($path)){
                    if(!mkdir($path, DEF_RIGHTS)) {
                        die('Failed to create '.basename($path).'...');
                    }
                    chmod($path,DEF_RIGHTS);
                }
            }
        }
        // make sure Maya module is not disabled
        if (isset($config['modules.disabled']) && in_array('Maya', $config['modules.disabled'])) {
            array_splice($config['modules.disabled'], array_search('Maya', $config['modules.disabled']), 1);
        }

        $app = new LimeExtra\App($config);

        $app['config'] = $config;

        // register paths
        foreach ($config['paths'] as $key => $path) {
            $app->path($key, $path);
        }
        // nosql storage
        $app->service('storage', function() use($config) {
            
            $client = new MongoHybrid\Client($config['database']['server'], $config['database']['options'], $config['database']['driverOptions']);
            return $client;
        });
        // check whether maya is already installed
        if(MAYA_ADMIN && !MAYA_API_REQUEST){
            if (!$app->path('#storage:data/maya.sqite')) {
                try {
                    if (!$app->storage->getCollection('maya/accounts')->count()) {
                        include(__DIR__."/install/index.php");
                        exit;
                    }
                } catch(Exception $e) { }
            }
        }

        // file storage
        $app->service('filestorage', function() use($config, $app) {

            $storages = array_replace_recursive([

                'root' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('#root:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('#root:', true)
                ],

                'site' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('site:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('site:', true)
                ],

                'tmp' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('#tmp:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('#tmp:', true)
                ],

                'thumbs' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('#thumbs:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('#thumbs:', true)
                ],

                'uploads' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('#uploads:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('#uploads:', true)
                ],

                'assets' => [
                    'adapter' => 'League\Flysystem\Adapter\Local',
                    'args' => [$app->path('#uploads:')],
                    'mount' => true,
                    'url' => $app->pathToUrl('#uploads:', true)
                ]

            ], $config['filestorage']);

            $app->trigger('maya.filestorages.init', [&$storages]);

            $filestorage = new FileStorage($storages);

            return $filestorage;
        });

        // key-value storage
        $app->service('memory', function() use($config) {
            $client = new MongoHybrid\Client($config['memory']['server'], $config['memory']['options'], $config['memory']['driverOptions']);
            return $client;
        });

        // mailer service
        $app->service('mailer', function() use($app, $config){
            
            $options = isset($config['mailer']) ? $config['mailer']:[];

            if (is_string($options)) {
                parse_str($options, $options);
            }

            $mailer    = new \Mailer($options['transport'] ?? 'mail', $options);
            return $mailer;
        });

        // set cache path
        $tmppath = $app->path('#tmp:');

        $app('cache')->setCachePath($tmppath);
        $app->renderer->setCachePath($tmppath);

        // i18n
        $app('i18n')->locale = $config['i18n'] ?? 'en';

        // handle exceptions
        if (MAYA_ADMIN) {

            set_exception_handler(function($exception) use($app) {

                $error = [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ];

                if ($app['debug']) {
                    $body = $app->request->is('ajax') || MAYA_API_REQUEST ? json_encode(['error' => $error['message'], 'file' => $error['file'], 'line' => $error['line']]) : $app->render('maya:views/errors/500-debug.php', ['error' => $error]);
                } else {
                    $body = $app->request->is('ajax') || MAYA_API_REQUEST ? '{"error": "500", "message": "system error"}' : $app->view('maya:views/errors/500.php');
                }

                $app->trigger('error', [$error, $exception]);

                header('HTTP/1.0 500 Internal Server Error');
                echo $body;

                if (function_exists('maya_error_handler')) {
                    maya_error_handler($error);
                }
            });
        }
        
        // ADDONS
        $modulesPaths = array_merge([
            MAYA_DIR.'/core',  # core
            MAYA_DIR.'/addons' # addons
        ], $config['loadmodules'] ?? []);

        if (MAYA_ENV_ROOT !== MAYA_DIR) {
            $modulesPaths[] = MAYA_ENV_ROOT.'/addons';
        }

        // load modules
        $app->loadModules($modulesPaths);
        
        
        
        

        // load config global bootstrap file
        if ($custombootfile = $app->path('#config:bootstrap.php')) {
            include($custombootfile);
        }

        $app->trigger('maya.bootstrap');
    }

    // shorthand modules method call e.g. maya('regions:render', 'test');
    if (func_num_args() > 1) {

        $arguments = func_get_args();

        list($module, $method) = explode(':', $arguments[0]);
        array_splice($arguments, 0, 1);
        return call_user_func_array([$app->module($module), $method], $arguments);
    }

    return $module ? $app->module($module) : $app;
}
$maya = maya();
$GLOBALS["maya"] = $maya;

try{
    // Takes raw data from the request
    $json = file_get_contents('php://input');
    unset($_REQUEST[$json]);
    // Converts it into a PHP object
    $data = json_decode($json, true);
    if(is_array($data))
        $data = @array_merge_recursive($_REQUEST, $data);
    if(is_array($data))
        $_REQUEST = $data;
}catch(Exception $e){}