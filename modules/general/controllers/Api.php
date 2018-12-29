<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
        $this->load->library('curl');
    }

    function get_file_post() {
        $input = $this->input->post('file_id');
        $dulieu = $this->mod_api->get_file($input);
        $url = $this->mod_api->get_forder($dulieu[0]->file_id);
        if ($dulieu == true) {
            $err_code = '200';
            $data = $dulieu;
        } else {
            $err_code = '101';
            $data = array();
        }
        $this->response(array('err_code' => $err_code, 'file' => $data , 'site_url' => _UPLOADS_PATH.$url));
    }
    function themthumuc_post() {
        $forder_name = $this->input->post('folder_name');
        $forder_path = $this->input->post('folder_path');
        $data = $this->mod_api->_themthumuc($forder_name, $forder_path);
        if($data == true) {
                $err_code = '100';
        }
        else {
                $err_code = '200';
        }
        $this->response(array('err_code' => $err_code));
    }

}
