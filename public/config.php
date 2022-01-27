<?php

define('ROOT_PATH', dirname(__DIR__) . "/");
define('VENDOR_PATH', ROOT_PATH . 'vendor/');

require  VENDOR_PATH . 'autoload.php';
use Yosymfony\Toml\Toml;

function getTomlConfig(): array {
    $config_path = ROOT_PATH . '/config.toml';
    try {
        $config = Toml::ParseFile($config_path);
        return $config;
    } catch (Exception $e) {
        print "Configuration file " . $config_path . " not found.\n" . 
              "See " . ROOT_PATH . "/config_example.toml" . " for an example configuration.\n";
    }
}

$config = getTomlConfig();
define('DB_USERNAME', $config['username']);
define('DB_PASSWORD', $config['password']);
define('DSN', $config['dsn']);

define('SRC_PATH', ROOT_PATH . 'source/');
define('CONTROLLERS_PATH', SRC_PATH . 'Controller/');
define('TEMPLATES_PATH', SRC_PATH . 'templates/');
define('SCRIPTS_PATH', SRC_PATH . 'scripts/');

define('HTTP_SERVER', 'http://localhost:' . $_SERVER['SERVER_PORT'] . '/');

// DEBUG
function debug($str) {
    $file = ROOT_PATH . 'debug.txt';
    $current = file_get_contents($file);
    $current .= $str;
    file_put_contents($file, $current);

}

?>