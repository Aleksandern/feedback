<?php
error_reporting (E_ALL);
session_start();

define ('DIRSEP', DIRECTORY_SEPARATOR); 
define ('site_path', dirname(__FILE__));

$doc_root = str_replace('/', DIRSEP, $_SERVER["DOCUMENT_ROOT"]);
$fb_path = str_replace($doc_root.DIRSEP, '', site_path);
$fb_path = str_replace(DIRSEP, '/', $fb_path);
define ('fb_path', $fb_path);

define ('app', 'app');
require_once site_path.DIRSEP.app.DIRSEP.'Bootstrap.php';
