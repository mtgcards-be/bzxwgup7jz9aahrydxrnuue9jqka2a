<?php

ini_set ('track_errors', 1);
ini_set ('log_errors', 1);

$whitelist = array(
    '127.0.0.1',
    '::1'
);

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
	ini_set ('error_log', '/customers/6/d/6/mtgcards.be/httpd.www/tools/logs/php_error_aEDH4552yPaM17So.log');
} else {
	ini_set ('error_log', 'tools/logs/php_error_aEDH4552yPaM17So.log');
}