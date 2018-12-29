<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Loaihopdong extends ADMIN_Controller {

    private $dsreview;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('loaihopdong');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_loaihopdong');
    }
    function index() {
        $this->parser->parse('loaihopdong/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_loaihopdong->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->loaihopdong_ten;
            $row[] = '
                <div class="btn-group">
                <button class="btn btn-xs btn-info" onclick="_sua(' . $item->loaihopdong_id . ',\'' . $item->loaihopdong_ten . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->loaihopdong_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_loaihopdong->count_all(),
            "recordsFiltered" => $this->mod_loaihopdong->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_loaihopdong->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong', "loaihopdong_id" => $kiemtra));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    function ajax_edit() {
        $id_loai_hopdong = $this->input->post("idloai_hopdongsua");
        $name_loai_hopdong = $this->input->post("nameloai_hopdongsua");
        $data = array(
            'loaihopdong_id' => $id_loai_hopdong,
            'loaihopdong_ten' => $name_loai_hopdong
        );
        $kiemtra = $this->mod_loaihopdong->_update($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function delete() {
        $this->mod_loaihopdong->_update(array('loaihopdong_id' => $this->input->post('id'), 'loaihopdong_status' => 2 ));
        echo json_encode(array('code' => '100'));
    }
}
