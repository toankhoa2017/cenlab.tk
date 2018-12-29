<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$config['api'] = array(
    'account' => array(
        'login' => _ACCOUNT_RESTFUL.'account/login',
        'changepwd' => _ACCOUNT_RESTFUL.'account/changepwd',
        'register' => _ACCOUNT_RESTFUL.'account/register',
        'check' => array(
            'email' => _ACCOUNT_RESTFUL.'account/check/email',
            'phone' => _ACCOUNT_RESTFUL.'account/check/phone',
            'cmnd' => _ACCOUNT_RESTFUL.'account/check/cmnd'
        ),
        'resetpwd' => _ACCOUNT_RESTFUL.'account/resetpwd',
        'getinfo' => _ACCOUNT_RESTFUL.'account/gets',
        'getmenu' => _ACCOUNT_RESTFUL.'account/sidebar',
        'listmods' => _ACCOUNT_RESTFUL.'account/listmods',
        'setpermission' => _ACCOUNT_RESTFUL.'account/setpermission'
    ),
    'group' => array(
        'gets' => _ACCOUNT_RESTFUL.'group/gets',
        'update' => _ACCOUNT_RESTFUL.'group/update',
        'getaccs' => _ACCOUNT_RESTFUL.'group/getaccs',
        'getoutaccs' => _ACCOUNT_RESTFUL.'group/getoutaccs',
        'addaccs' => _ACCOUNT_RESTFUL.'group/addaccs',
        'removeacc' => _ACCOUNT_RESTFUL.'group/removeacc'
    ),
    'module' => array(
        'gets' => _ACCOUNT_RESTFUL.'module/gets',
        'update' => _ACCOUNT_RESTFUL.'module/update'
    ),
	'general' => _SELF_RESTFUL.'general/api/',
	'nhansu' => _SELF_RESTFUL.'nhansu/api/',
	'nhansu_restful' => _SELF_RESTFUL.'nhansu/restful/',
	'khachhang' => _SELF_RESTFUL.'khachhang/api/',
	'nenmau' => _SELF_RESTFUL.'nenmau/api/',
	'nhanmau' => _SELF_RESTFUL.'nhanmau/api/',
	'luumau' => _SELF_RESTFUL.'luumau/api/',
	'thietbi' => _SELF_RESTFUL.'thietbi/api/',
	'tailieu' => _SELF_RESTFUL.'tailieu/api/'
);