<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dieukienluu extends MY_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('dieukienluu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_dieukienluu');
    }

    function index() {
        $this->parser->parse('dieukienluu/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_dieukienluu->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->dieukienluu_name;
            $row[] = '
                <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-info" onclick="_sua(' . $item->dieukienluu_id . ',\'' . $item->dieukienluu_name . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->dieukienluu_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_dieukienluu->count_all(),
            "recordsFiltered" => $this->mod_dieukienluu->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_dieukienluu->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoadieukienluu() {
        $id_dieukienluu = $this->input->post("iddieukienluuxoa");
        $this->mod_dieukienluu->xoadieukienluu($id_dieukienluu);
        echo "1";
    }

    function suadieukienluu() {
        $id_dieukienluu = $this->input->post("iddieukienluusua");
        $name_dieukienluu = $this->input->post("namedieukienluusua");
        $mota_dieukienluu = $this->input->post("motadieukienluusua");
        $name_truocthaydoi = $this->input->post("tendieukienluutruocthaydoi");
        $name_eng = $this->input->post("name_eng");
        $data = array(
            'dieukienluu_id' => $id_dieukienluu,
            'dieukienluu_name' => $name_dieukienluu,
        );
        $kiemtra = $this->mod_dieukienluu->suadieukienluu($data, $name_truocthaydoi);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
