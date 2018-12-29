<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends API_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("mod_api");
    }

    function congty_add_post() {
        $input = $this->input->post();
        $check_phone = $this->mod_api->check_phone_congty($input['phone'], '0');
        $check_email = $this->mod_api->check_email_congty($input['email'], '0');
        $check_tax = $this->mod_api->check_tax_congty($input['tax'], '0');
        $kiemtra = true;
        $dulieu = array();
        if ($check_phone == false) {
            $kiemtra = false;
            $err_code = '102'; //phone đã tồn tại
        } else if ($check_email == false) {
            $kiemtra = false;
            $err_code = '103'; //email đã tồn tại
        } else if ($check_tax == false) {
            $kiemtra = false;
            $err_code = '104'; //tax đã tồn tại
        };
        if ($kiemtra == true && $input['phone']!="" && $input['phone']!=NULL) {
            $dulieu = $this->mod_api->congty_add($input);
            if ($dulieu == true) {
                $err_code = '200';
            } else {
                $err_code = '101';
            }
        }else{
            $err_code = '101';
        }
        $this->response(array('err_code' => $err_code, 'congty' => $dulieu));
    }

    function check_update_register_post() {
        $input = $this->input->post();
        $dulieu = $this->mod_api->check_update_register($input);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //thành công
        } else {
            $this->response(array('err_code' => '101')); //thất bại
        }
    }

    function get_info_post() {
        $congty_id = $this->input->post('congty_id');
        $dulieu = $this->mod_api->get_info($congty_id);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200', 'info' => $dulieu));
        } else {
            $this->response(array('err_code' => '101', ''));
        }
    }

    function list_contact_post() {//xong
        $key_contact = $this->input->post('key_contact_name');
        $congty_id = $this->input->post('congty_id');
        $contact_id = $this->input->post('contact_id');
        if (isset($contact_id) && $contact_id > 0) {
            $danhsach = $this->mod_api->get_contact($contact_id);
        } else {
            if ($congty_id == "") {
                $danhsach = $this->mod_api->list_contact($key_contact);
            } else {
                $danhsach = $this->mod_api->list_contact($key_contact, $congty_id);
            }
        }
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_contact' => $danhsach));
        } else {
            $danhsach = array();
            $this->response(array('err_code' => '101', 'list_contact' => $danhsach));
        }
    }

    function list_congty_post() {//xong
        $key_congty = $this->input->post('key_congty_name');
        $congty_id = $this->input->post('congty_id');
        if (isset($congty_id) && $congty_id > 0) {
            $danhsach = $this->mod_api->get_congty($congty_id);
        } else {
            $danhsach = $this->mod_api->list_congty($key_congty);
        }
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_congty' => $danhsach));
        } else {
            $danhsach = array();
            $this->response(array('err_code' => '101', 'list_congty' => $danhsach));
        }
    }
    
    function list_all_cty_get(){
        $danhsach = $this->mod_api->list_all_congty();
        if ($danhsach == true) {
            $this->response(array('err_code' => '200', 'list_congty' => $danhsach));
        } else {
            $danhsach = array();
            $this->response(array('err_code' => '101', 'list_congty' => $danhsach));
        }
    }
            
    function contact_add_post() {
        $input = $this->input->post();
        $check_phone = $this->mod_api->check_phone_contact($input['phone'], '0');
        $check_email = $this->mod_api->check_email_contact($input['email'], '0');
        $kiemtra = true;
        $dulieu = array();
        if ($check_phone == false) {
            $err = 102; //phone đã tồn tại
            $kiemtra = false;
        } else if ($check_email == false) {
            $err = 103; //email đã tồn tại
            $kiemtra = false;
        };
        if ($kiemtra == true) {
            $dulieu = $this->mod_api->contact_add($input);
            if ($dulieu == true) {
                $err = 200;
            } else {
                $err = 101;
            }
        }
        $this->response(array('err_code' => $err, 'contact' => $dulieu));
    }

    function contact_remove_post() {
        $input = $this->input->post();
        $dulieu = $this->mod_api->contact_remove($input);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số phone chưa có
        } else {
            $this->response(array('err_code' => '101')); //số phone đã tồn tại
        }
    }

    function contact_update_post() {
        $input_update = $this->input->post();
        $dulieu = $this->mod_api->contact_update($input_update);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số phone chưa có
        } else {
            $this->response(array('err_code' => '101')); //số phone đã tồn tại
        }
    }

    function check_phone_contact_post($phone_contact, $contact_id) {
        if (!isset($phone_contact)) {
            $phone_contact = $this->input->post('contact_phone');
        }
        if (!isset($contact_id)) {
            $contact_id = $this->input->post('contact_id');
        }
        $dulieu = $this->mod_api->check_phone_contact($phone_contact, $contact_id);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số phone chưa có
        } else {
            $this->response(array('err_code' => '101')); //số phone đã tồn tại
        }
    }

    function check_email_contact_post($email_contact, $contact_id) {
        if (!isset($email_contact)) {
            $email_contact = $this->input->post('contact_email');
        }
        if (!isset($contact_id)) {
            $contact_id = $this->input->post('contact_id');
        }
        $dulieu = $this->mod_api->check_email_contact($email_contact, $contact_id);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số email chưa có
        } else {
            $this->response(array('err_code' => '101')); //số email đã tồn tại
        }
    }

    function check_phone_congty_post($phone_congty, $id_congty) {
        if (!isset($phone_congty)) {
            $phone_congty = $this->input->post('congty_phone');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('id_congty');
        }
        $dulieu = $this->mod_api->check_phone_congty($phone_congty, $id_congty);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số phone chưa có
        } else {
            $this->response(array('err_code' => '101')); //số phone đã tồn tại
        }
    }

    function check_email_congty_post($congty_email, $id_congty) {
        if (!isset($congty_email)) {
            $congty_email = $this->input->post('congty_email');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('id_congty');
        }
        $dulieu = $this->mod_api->check_email_congty($congty_email, $id_congty);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số email chưa có
        } else {
            $this->response(array('err_code' => '101')); //số email đã tồn tại
        }
    }

    function check_tax_congty_post($tax_congty, $id_congty) {
        if (!isset($tax_congty)) {
            $tax_congty = $this->input->post('congty_tax');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('id_congty');
        }
        $dulieu = $this->mod_api->check_tax_congty($tax_congty, $id_congty);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200')); //số tax chưa có
        } else {
            $this->response(array('err_code' => '101')); //số tax đã tồn tại
        }
    }

    function add_congty_contact_post() {
        $congty_id = $this->input->post('congty_id');
        $contact_id = $this->input->post('contact_id');
        $dulieu = $this->mod_api->add_congty_contact($congty_id, $contact_id);
        if ($dulieu == true) {
            $this->response(array('err_code' => '200'));
        } else {
            $this->response(array('err_code' => '101'));
        }
    }

}
