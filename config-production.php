<?php

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Load environment variables using vlucas/phpdotenv
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

// Helper function to get environment variables
function env($key, $default = null) {
    return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
}

// Database
$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = env('DB_HOST', 'localhost');
$CFG->dbname    = env('DB_DATABASE', 'moodle');
$CFG->dbuser    = env('DB_USERNAME', 'moodle_user');
$CFG->dbpass    = env('DB_PASSWORD', '');
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => (int)env('DB_PORT', 3306),
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

// Site
$CFG->wwwroot   = env('WWWROOT', 'https://example.com');
$CFG->dataroot  = env('DATAROOT', dirname(__DIR__) . '/storage/moodledata');
$CFG->admin     = env('ADMIN', 'admin');

// Security and Performance Settings for Production
$CFG->directorypermissions = 0755;

// Production optimizations
$CFG->debug = 0;
$CFG->debugdisplay = 0;
$CFG->cachejs = 1;
$CFG->themedesignermode = 0;

// SSL Configuration
$CFG->sslproxy = false;

// Optional: Performance settings
// $CFG->sessioncookiesecure = true;
// $CFG->cookiesecure = true;

require_once(__DIR__ . '/lib/setup.php');
