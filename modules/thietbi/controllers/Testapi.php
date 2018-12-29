<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testapi extends MY_Controller{
    private $api_nhansu = 'http://dev.api/nenmau/';
    function __construct() {
        parent::__construct();
		//$this->load->model('mod_bothietbi');
		//$this->load->model('mod_device');
        $this->load->library('curl');
    }
    
    function index(){
        $this->curl->create('http://tools.ciss.edu.vn:2110/api/test');
       // print_r($this->_restful['account']['login']);
            $this->curl->post(array(
                'username' => '9999',
                'password' => '9999',
                'project' => 1
            ));
            $result = json_decode($this->curl->execute(), TRUE);
			print_r($result);
    }
  	
}