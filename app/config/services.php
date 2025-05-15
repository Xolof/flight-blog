<?php

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use Tracy\Debugger;

/** 
 * @var array $config This comes from the returned array at the bottom of the config.php file
 * @var Engine $app
 */

// uncomment the following line for MySQL
// $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// uncomment the following line for SQLite
$dsn = 'sqlite:' . $config['database']['file_path'];

// Uncomment the below lines if you want to add a Flight::db() service
// In development, you'll want the class that captures the queries for you. In production, not so much.
$pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
$app->register('db', $pdoClass, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ],
    function($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('PRAGMA foreign_keys = ON');
    }
);

// Got google oauth stuff? You could register that here
// $app->register('google_oauth', Google_Client::class, [ $config['google_oauth'] ]);

// Redis? This is where you'd set that up
// $app->register('redis', Redis::class, [ $config['redis']['host'], $config['redis']['port'] ]);

$Latte = new \Latte\Engine;
$Latte->setTempDirectory(__DIR__ . '/../cache/');
// This adds a new function in our Latte template files
// that allows us to generate a URL from an alias.
$Latte->addFunction('route', function(string $alias, array $params = []) use ($app) {
    return $app->getUrl($alias, $params);
});
// This is fun feature of Flight. You can remap some built in functions with the framework
// to your liking. In this case, we're remapping the Flight::render() method.
$app->map('render', function(string $templatePath, array $data = [], ?string $block = null) use ($app, $Latte) {
    $templatePath = __DIR__ . '/../views/'. $templatePath;
    $Latte->render($templatePath, $data, $block);
});
