<?php

define("ROOT_PATH", dirname(__DIR__ ));

require  ROOT_PATH . '/vendor/autoload.php';

use Yosymfony\Toml\Toml;


$config_path = ROOT_PATH . '/config.toml';
try {
    $config = Toml::ParseFile($config_path);
} catch (Exception $e) {
    print "Configuration file " . $config_path . " not found.\n" . 
          "See " . ROOT_PATH . "/config_example.toml" . " for an example configuration.\n";
}


try {
    $connection_string='mysql:host='.$config['host'].';port=8889'.';dbname='.$config
    ['dbname'];
    print($connection_string."\n");
    $conn = oci_connect($config['username'],$config['password'],"//labora.mimuw.edu.pl/LABS");
    if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
    }
    $dbh = new PDO($connection_string, $config['username'], $config['password']);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>