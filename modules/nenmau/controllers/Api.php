<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
        $this->load->model("mod_dongia");
        $this->load->model("mod_chat");
    }

    function phuongphap_add_post() {
        $input = $this->input->post();
        $data = array(
            'phuongphap_code' => $input['phuongphap_code'],
            'phuongphap_name' => $input['phuongphap_name'],
            'phuongphap_loai' => $input['phuongphap_loai'],
            'phuongphap_shortname' => $input['phuongphap_shortname']
        );
        $kiemtra = $this->mod_api->phuongphap_add($data);
        ($kiemtra == true) ? $err = "200" : $err = "101";
        echo json_encode(array("err_code" => $err));
    }
    
    function phuongphap_update_post(){
        $input = $this->input->post();
        $data = array(
            'phuongphap_status' => $input['phuongphap_status'],
        );
        $dieukien = array(
            'phuongphap_code' => $input['phuongphap_code'],
        );
        $kiemtra = $this->mod_api->phuongphap_update($data, $dieukien);
        ($kiemtra == true) ? $err = "200" : $err = "101";
        echo json_encode(array("err_code" => $err));
    }
            
    function danhsach_nenmau_get(){
        $key = $this->input->get('key');
        $danhsach = $this->mod_api->danhsach_nenmau($key);
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_nenmau' => $danhsach));
        } else {
            $danhsach = array();
            $this->response(array('err_code' => '101', 'list_nenmau' => $danhsach));
        }
    }
    
    function danhsach_nhomchitieu_get(){
        $nenmau = $this->input->get('nenmau');
        $getall = $this->input->get('getall');
        //$getall = ($this->privdinhgia['read'] || $this->privtheodoichitieu['read']) ? TRUE : FALSE;
        $danhsach = $this->mod_dongia->get_datatables($nenmau, $getall);
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_nhomchitieu' => $danhsach));
        } else {
            $this->response(array('err_code' => '101', 'list_nhomchitieu' => $danhsach));
        }
    }
    
    function danhsach_chitieu_get(){
        $dongia = $this->input->get('dongia');
        $danhsach = $this->mod_chat->get_datatables($dongia);
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_chitieu' => $danhsach));
        } else {
            $this->response(array('err_code' => '101', 'list_chitieu' => $danhsach));
        }
    }
}
