<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Phpoffice {
	function __construct($url = '') {
		require_once "PhpOffice/autoload.php";
	}
}