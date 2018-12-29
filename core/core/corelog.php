<?php
/*
Smarty
|	New
|	|	Folders
|	|	|	libraries/Smarty
|	|	Files
|	|	|	libraries/Smarty.php //Call from application/libraries/MY_Parser.php (Define when using smarty)

Restful
|	New
|	|	Folders
|	|	Files
|	|	|	libraries/Curl.php
|	|	|	libraries/Format.php //Call from REST_Controller
|	|	|	application/config/restful.php //Define when using restful
|	Changed
|	|	core/Controller.php
|	|	Describe changed
|	|	|	Add new abstract class REST_Controller extends MX_Controller //Line 98

HMVC
|	Changed
|	|	core/Loader.php
|	|	Describe changed
|	|	|	Add new protected $_ci_interface_paths = array(APPPATH, BASEPATH); //Line 87
|	|	|	Add new public function iface($interface = '') //Line 375

New
|	Files
|	|	libraries/Mahoa.php
*/
?>