<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Phpword {
	function __construct($url = '') {
		require_once "PhpWord/autoload.php";
	}
}
