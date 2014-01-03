<?php

define('SS_ENVIRONMENT_TYPE', 'dev');

$host = (getenv('OPENSHIFT_MYSQL_DB_HOST')) ? getenv('OPENSHIFT_MYSQL_DB_HOST') : putenv('OPENSHIFT_MYSQL_DB_HOST=localhost') ;
$port = (getenv('OPENSHIFT_MYSQL_DB_PORT')) ? getenv('OPENSHIFT_MYSQL_DB_PORT') : putenv('OPENSHIFT_MYSQL_DB_PORT=3306');
$user = (getenv('OPENSHIFT_MYSQL_DB_USERNAME')) ? getenv('OPENSHIFT_MYSQL_DB_USERNAME') : putenv('OPENSHIFT_MYSQL_DB_USERNAME=root') ;
$pass = (getenv('OPENSHIFT_MYSQL_DB_PASSWORD')) ? getenv('OPENSHIFT_MYSQL_DB_PASSWORD') : putenv('OPENSHIFT_MYSQL_DB_PASSWORD=d3v-R3ntcampus') ;


define('SS_DATABASE_SERVER', $host);
define('SS_DATABASE_PORT', $port);
define("SS_DATABASE_USERNAME", $user);
define('SS_DATABASE_PASSWORD', $pass);
//define('SS_DEFAULT_ADMIN_USERNAME', 'admin');
//define('SS_DEFAULT_ADMIN_PASSWORD', 'sw0rdfish');

global $_FILE_TO_URL_MAPPING;
$_FILE_TO_URL_MAPPING[$_ENV['OPENSHIFT_REPO_DIR'].'php'] = 'http://parcel-maxglass.rhcloud.com/';

