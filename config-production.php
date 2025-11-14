<?php

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Database
$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv('DB_HOST') ?: 'localhost';
$CFG->dbname    = getenv('DB_DATABASE') ?: 'moodle';
$CFG->dbuser    = getenv('DB_USERNAME') ?: 'moodle_user';
$CFG->dbpass    = getenv('DB_PASSWORD') ?: '';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => (int)(getenv('DB_PORT') ?: 3306),
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

// Site
$CFG->wwwroot   = getenv('WWWROOT') ?: 'https://$FORGE_SITE_NAME';
$CFG->dataroot  = getenv('DATAROOT') ?: '/home/forge/$FORGE_SITE_NAME/storage/moodledata';
$CFG->admin     = getenv('ADMIN') ?: 'admin';

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
