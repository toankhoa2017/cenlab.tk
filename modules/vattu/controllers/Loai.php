<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Loai extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('loai');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_loai');
    }

    function index() {
        $this->parser->parse('loai/list');
    }
    
    function ajax_list() {
        $thaotac = $this->lang->getLang();
        $list = $this->mod_loai->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="name_loai" data-id="'.$item->loai_id.'">'.$item->loai_name.'</div>';
            $row[] = $item->loai_symbol ;
            $button = '';
            $button .= ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->loai_id . ',\'' . $item->loai_name . '\',\'' . $item->loai_symbol . '\')" data-toggle="tooltip" title="'.$thaotac['tooltip_update'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button> <button class="btn btn-xs btn-danger delete" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_loai->count_all(),
            "recordsFiltered" => $this->mod_loai->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_loai->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    
    function sualoaisanpham(){
        $data = array(
            'loai_id' => $this->input->post("loai_id"),
            'loai_name' => $this->input->post("loai_name"),
            'loai_symbol' => $this->input->post("loai_symbol"),
        );
        $kiemtra = $this->mod_loai->sualoaisanpham($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
	function delete() {
		$id = $this->input->post('id');
		$this->mod_loai->_update($id, array('loai_status' => '2'));
		echo json_encode(array('code' =>'100', 'mess' => ''));
	}
}
