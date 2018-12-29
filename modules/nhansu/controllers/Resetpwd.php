<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Resetpwd extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_nhansu');
    }
    function index() {
        $pwd = getRandom(5);
        $this->curl->create($this->_api['account']['resetpwd']);
        $this->curl->post(array(
            'password_new' => $pwd,
            'aid' => $this->input->post('code'),
            'project' => _PROJECT_ID
        ));
        $reset = json_decode($this->curl->execute(), TRUE);
        if ($reset['err_code'] == '100') {
            $data = array(
                'nhansu_id' => $this->input->post('id'),
                'nhansu_password' => $pwd
            );
            $this->mod_nhansu->_resetPwd($data);
            echo json_encode(array("pwd" => $pwd));
        }
    }
}
