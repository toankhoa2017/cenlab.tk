<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Phuongphap extends MY_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('phuongphap');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_phuongphap');
    }

    function index() {
        $this->parser->parse('phuongphap/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_phuongphap->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->phuongphap_code;
            $row[] = $item->phuongphap_name;
            $row[] = $item->phuongphap_name_eng;
            $row[] = $item->phuongphap_loai == 1 ? "Nội Bộ" : "Bên Ngoài";
            $row[] = '
                <div class="hidden-sm hidden-xs btn-group">
                <button class="btn btn-xs btn-info" onclick="_sua(' . $item->phuongphap_id . ',\'' . $item->phuongphap_code . '\',\'' . $item->phuongphap_name . '\',\'' . $item->phuongphap_describe . '\',\'' . $item->phuongphap_name_eng . '\',\'' . $item->phuongphap_loai . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>
                <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->phuongphap_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </div>
            ';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_phuongphap->count_all(),
            "recordsFiltered" => $this->mod_phuongphap->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_nenmau->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
        } else {
            $items['ref'] = '-';
        }
        $kiemtra = $this->mod_phuongphap->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoaphuongphap() {
        $id_phuongphap = $this->input->post("idphuongphapxoa");
        $this->mod_phuongphap->xoaphuongphap($id_phuongphap);
        echo "1";
    }

    function suaphuongphap() {
        $id_phuongphap = $this->input->post("idphuongphapsua");
        $code_phuongphap = $this->input->post("codephuongphap");
        $name_phuongphap = $this->input->post("namephuongphapsua");
        $loai_phuongphap = $this->input->post("loaiphuongphapsua");
        $mota_phuongphap = $this->input->post("motaphuongphapsua");
        //$code_truocthaydoi = $this->input->post("codephuongphaptruocthaydoi");
        $name_eng = $this->input->post("name_eng");
        $data = array(
            'phuongphap_id' => $id_phuongphap,
            'phuongphap_name' => $name_phuongphap,
            'phuongphap_loai' => $loai_phuongphap,
            'phuongphap_describe' => $mota_phuongphap,
            'phuongphap_name_eng' => $name_eng
        );
        $kiemtra = $this->mod_phuongphap->suaphuongphap($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
