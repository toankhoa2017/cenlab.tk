<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Khachhang extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('khachhang');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_khachhang');
    }

    function index() {
        $this->parser->parse('congty/list');
    }

    function ajax_list() {
        $this->lang->load('khachhang');
        $url_action = "khachhang/index_nenmau";
        $thaotac = $this->lang->getLang();
        $list = $this->mod_khachhang->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('khachhang/chitiet/' . $item->congty_id) . '">' . $item->congty_name . '</a>';
            $row[] = $item->congty_address;
            $row[] = $item->congty_phone;
            $row[] = $item->congty_fax;
            $row[] = $item->congty_email;
            $row[] = $item->congty_tax;
            $row[] = '
                <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-info" onclick="_sua(' . $item->congty_id . ',\'' . $item->congty_name . '\',\'' . $item->congty_address . '\',\'' . $item->congty_phone . '\',\'' . $item->congty_fax . '\',\'' . $item->congty_email . '\',\'' . $item->congty_tax . '\')" data-toggle="tooltip" title="' . $thaotac['tooltip_sua'] . '"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->congty_id . ')" data-toggle="tooltip" title="' . $thaotac['tooltip_xoa'] . '"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                <button class="btn btn-xs btn-warning" onclick="_contact(' . $item->congty_id . ')" data-toggle="tooltip" title="' . $thaotac['tooltip_nguoilienhe'] . '"><i class="ace-icon fa fa-flag bigger-120"></i></button> 
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_khachhang->count_all(),
            "recordsFiltered" => $this->mod_khachhang->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $data = $this->input->post();
        $input = $this->input->post();
        $check_phone = $this->mod_khachhang->check_phone_congty($input['phone'], '0');
        $check_email = $this->mod_khachhang->check_email_congty($input['email'], '0');
        $check_tax = $this->mod_khachhang->check_tax_congty($input['tax'], '0');
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
            $dulieu = $this->mod_khachhang->congty_add($input);
            if ($dulieu == true) {
                $err_code = '200';
            } else {
                $err_code = '101';
            }
        }else{
            $err_code = '101';
        }
        if (isset($err_code) && $err_code == 200) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function xoacongty() {
        $id_congty = $this->input->post("idcongtyxoa");
        $this->mod_khachhang->xoacongty($id_congty);
        echo "1";
    }

    function suacongty() {
        $data = $this->input->post();
        $dulieu = array(
            'id' => $data['id'],
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'fax' => $data['fax'],
            'email' => $data['email'],
            'tax' => $data['tax'],
        );
        $dulieu = $this->mod_khachhang->check_update_register($dulieu);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function contacts($congty_id) {
        //Language
        $this->lang->load('contact');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $dulieu = $this->mod_khachhang->get_info($congty_id);
        if ($dulieu == true) {
            $info = array();
            foreach ($dulieu as $row) {
                $info['congty_id'] = $row['congty_id'];
                $info['congty_name'] = $row['congty_name'];
                $info['congty_address'] = $row['congty_address'];
                $info['congty_phone'] = $row['congty_phone'];
                $info['congty_fax'] = $row['congty_fax'];
                $info['congty_email'] = $row['congty_email'];
                $info['congty_tax'] = $row['congty_tax'];
                $info['congty_status'] = $row['congty_status'];
            };
            $this->parser->assign('info', $info);
            $this->parser->parse('contact/list');
        } else {
            
        }
    }

    function check_phone($phone_congty, $id_congty) {
        if (!isset($phone_congty)) {
            $phone_congty = $this->input->post('congty_phone');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('congty_id');
        }
        $dulieu = $this->mod_khachhang->check_phone_congty($phone_congty, $id_congty);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function check_email($congty_email, $id_congty) {
        if (!isset($congty_email)) {
            $congty_email = $this->input->post('congty_email');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('congty_id');
        }
        $dulieu = $this->mod_khachhang->check_email_congty($congty_email, $id_congty);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function check_tax($tax_congty, $id_congty) {
        if (!isset($tax_congty)) {
            $tax_congty = $this->input->post('congty_tax');
        }
        if (!isset($id_congty)) {
            $id_congty = $this->input->post('congty_id');
        }
        $dulieu = $this->mod_khachhang->check_tax_congty($tax_congty, $id_congty);
        if ($dulieu == true) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'denied'));
        }
    }

    function chitiet($congty_id) {
        //Language
        $this->lang->load('khachhang');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $data = $this->mod_khachhang->chitietcongty($congty_id);
        $info = array();
        foreach ($data as $row) {
            $info['congty_id'] = $row->congty_id;
            $info['congty_name'] = $row->congty_name;
            $info['congty_address'] = $row->congty_address;
            $info['congty_phone'] = $row->congty_phone;
            $info['congty_fax'] = $row->congty_fax;
            $info['congty_email'] = $row->congty_email;
            $info['congty_tax'] = $row->congty_tax;
            $info['congty_status'] = $row->congty_status;
        };
        $this->parser->assign('info', $info);
        $this->parser->parse('congty/chitiet');
    }
    
    function index_nenmau() {
        if (!$this->privnenmau['read']) redirect(site_url() . 'admin/denied?w=read');
        $dongiaChats = $this->mod_chat->getGiaByDongia(0);// bo_id = 0 cho định giá nền mẫu
        $chatGias = array();
        foreach ($dongiaChats as $dongia){
            $chatGias[] = $dongia["gia_price"];
        }
        $this->parser->assign('chatGias', $chatGias);
        $this->parser->assign('package', 0); // bo_id = 0 cho định giá nền mẫu
        $this->parser->parse('nenmau/list');
    }

    function ajax_add_nenmau() {
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_nenmau->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
        } else {
            $items['ref'] = '-';
        }
        $kiemtra = $this->mod_nenmau->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong', 'ds' => $items));
        } else {
            echo json_encode(array("status" => 'denied', 'ds' => $items));
        }
    }
}
