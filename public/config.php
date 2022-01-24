<?php

function debug($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

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
define('DB_USERNAME', $config['usernmae']);
define('DB_PASSWORD', $config['password']);
define('DSN', $config['dsn']);

define('SRC_PATH', ROOT_PATH . 'source/');
define('TEMPLATES_PATH', SRC_PATH . 'templates/');
define('CONTROLLERS_PATH', SRC_PATH . 'Controller/');
define('HTTP_SERVER', 'http://localhost/');

// try {
//     $connection_string='mysql:host='.$config['host'].';port=8889'.';dbname='.$config
//     ['dbname'];
//     print($connection_string."\n");
//     $conn = oci_connect($config['username'],$config['password'],"//labora.mimuw.edu.pl/LABS");
//     if (!$conn) {
//         echo "oci_connect failed\n";
//         $e = oci_error();
//         echo $e['message'];
//     }
//     $dbh = new PDO($connection_string, $config['username'], $config['password']);
// } catch (PDOException $e) {
//     print "Error!: " . $e->getMessage() . "<br/>";
//     die();
// }
?>