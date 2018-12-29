<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Loaiforder extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_loaiforder');
    }

    function index() {
        $this->parser->parse('loaiforder/list');
    }

    function ajax_list() {
        $list = $this->mod_loaiforder->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->file_forder_name;
            $catchuoi = explode("/",$item->file_forder_path);
            $row[] = $catchuoi[0];
            $row[] = '
                <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-info" onclick="_sua(' . $item->file_forder_id . ',\'' . $item->file_forder_name . '\',\'' . $catchuoi[0] . '\')" data-toggle="tooltip" title="Sửa"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->file_forder_id . ')" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_loaiforder->count_all(),
            "recordsFiltered" => $this->mod_loaiforder->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_loaiforder->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoaloaiforder() {
        $id_loaiforder = $this->input->post("id_xoa");
        $this->mod_loaiforder->xoaloaiforder($id_loaiforder);
        echo "1";
    }
    
    function sualoaiforder() {
        $id = $this->input->post("id");
        $name = $this->input->post("name");
        $path = $this->input->post("path");
        $data = array(
            'file_forder_id' => $id,
            'file_forder_name' => $name,
            'file_forder_path' => $path.'/',
        );
        $kiemtra = $this->mod_loaiforder->sualoaiforder($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
}
