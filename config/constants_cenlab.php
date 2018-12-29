<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['_projectID'] = 1;
define('_PROJECT_ID', $config['_projectID']);
define("_MYSQL", json_encode(array(
	'account' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_account'),
	'nhansu' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_nhansu'),
	'khachhang' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_khachhang'),
	'nenmau' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_nenmau'),
	'luumau' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_luumau'),
	'tailieu' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_tailieu'),
	'vattu' => array('username' => 'root', 'password' => '123456', 'database' => 'cenlab_vattu')
)));
define('_ASSETS_PATH', 'http://assets.cenlab.vn/');
define('_ACCOUNT_RESTFUL', 'http://cenlab.tk/restful/');
define('_CENLAB_RESTFUL', 'http://cenlab.tk/');
define('_SELF_RESTFUL', 'http://cenlab.tk/');
define('_UPLOADS_PATH', './_uploads/');
define('_UPLOADS_URL', '_uploads/');