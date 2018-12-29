<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends API_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_api');
    }
    function getTang_post() {
        $arrayInfo = $this->post();
		$list_tang = $this->mod_api->_getTang();
		$tang = array();
		foreach ($list_tang as $t) {
			$tang[$t['loai_tai_lieu']][] = $t;
		}
        if (count($tang) > 0) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'tang' => $tang));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
}