<?php

defined ('S_NICK') || exit('no rights!');

$configuration = [
    'displayErrorDetails' => TRUE,
    'routerCacheFile' => 'cache.txt'
];
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_NAME','test.cv_base');
define('QUANTITY',3); // quantity items on a page
define('QUANTITY_LINKS',2); // quantity links on a pages before and after curent page
define('IMAGES', 'img'); 
define('NEWS_QUANTITY',4);
define('SALT2', 'awOIHO@EN@Oine q2enq2kbkb');
$configuration['db']['host']   = 'localhost';
$configuration['db']['user']   = 'root';
$configuration['db']['pass']   = '';
$configuration['db']['dbname'] = "test.cv_base";