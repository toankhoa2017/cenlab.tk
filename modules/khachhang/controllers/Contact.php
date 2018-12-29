<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('contact');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_contact');
    }

    function index($id) {
        $this->parser->assign('id', $this->input->post('id'));
        $this->parser->parse('contact/list');
    }

    function ajax_list() {
        $this->lang->load('khachhang');
        $thaotac = $this->lang->getLang();
        $congty_id = $this->input->post('congty_id');
        $list = $this->mod_contact->list_contact($congty_id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->contact_lastname . " " . $item->contact_firstname;
            $row[] = date('d-m-Y', strtotime($item->contact_birthday));
            $row[] = $item->contact_email;
            $row[] = $item->contact_phone;
            $row[] = $item->contact_password;
            $row[] = '
                <div class="hidden-sm hidden-xs btn-group">
                    <button class="btn btn-xs btn-info" onclick="_sua(' . $item->contact_id . ',\'' . $item->contact_lastname . '\',\'' . $item->contact_firstname . '\',\'' . $item->contact_email . '\',\'' . $item->contact_phone . '\',\'' . date('d-m-Y', strtotime($item->contact_birthday)) . '\')" data-toggle="tooltip" title="' . $thaotac['tooltip_sua'] . '"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->contact_id . ')" data-toggle="tooltip" title="' . $thaotac['tooltip_xoa'] . '"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_contact->count_all($congty_id),
            "recordsFiltered" => $this->mod_contact->count_filtered($congty_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {        
        $input = $this->input->post();
        $check_phone = $this->mod_contact->check_phone_contact($input['phone'], '0');
        $check_email = $this->mod_contact->check_email_contact($input['email'], '0');
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
            $dulieu = $this->mod_contact->contact_add($input);
            if ($dulieu == true) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'denied'));
            }
        }
    }

    function xoanguoilienhe() {
        $id_contact = $this->input->post("id_contact");
        $kiemtra = $this->mod_contact->xoanguoilienhe($id_contact);
        if ($kiemtra == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function suacontact() {
        $data = $this->input->post();
        $ngaysinh = $data['contact_birthday'];
        $ngay = explode("-", $ngaysinh);
        $ngayformat = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayformat = $ngayformat . trim($ngay[$i]);
            } else {
                $ngayformat = $ngayformat . trim($ngay[$i]) . "-";
            }
        }
        $dulieu = array(
            'contact_id' => $data['contact_id'],
            'contact_lastname' => $data['contact_lastname'],
            'contact_firstname' => $data['contact_firstname'],
            'contact_email' => $data['contact_email'],
            'contact_phone' => $data['contact_phone'],
            'contact_birthday' => trim($ngayformat),
        );
        $kiemtra = $this->mod_contact->contact_update($dulieu);
        if ($kiemtra==true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function check_phone($phone_contact, $contact_id) {
        if (!isset($phone_contact)) {
            $phone_contact = $this->input->post('contact_phone');
        }
        if (!isset($contact_id)) {
            $contact_id = $this->input->post('contact_id');
        }
        $dulieu = $this->mod_contact->check_phone_contact($phone_contact, $contact_id);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function check_email($email_contact, $contact_id) {
        if (!isset($email_contact)) {
            $email_contact = $this->input->post('contact_email');
        }
        if (!isset($contact_id)) {
            $contact_id = $this->input->post('contact_id');
        }
        $dulieu = $this->mod_contact->check_email_contact($email_contact, $contact_id);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

}
