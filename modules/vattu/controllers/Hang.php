<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hang extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('hang');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_hang');
    }

    function index() {
        $this->parser->parse('hang/list');
    }
    
    function ajax_list() {
        $thaotac = $this->lang->getLang();
        $list = $this->mod_hang->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="name_hang" data-id="'.$item->hang_id.'">'.$item->hang_name.'</div>';
            $button = '';
            $button .= ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->hang_id . ',\'' . $item->hang_name . '\')" data-toggle="tooltip" title="'.$thaotac['tooltip_update'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button> <button class="btn btn-xs btn-danger delete" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_hang->count_all(),
            "recordsFiltered" => $this->mod_hang->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_hang->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    
    function suahangsanxuat(){
        $data = array(
            'hang_id' => $this->input->post("hang_id"),
            'hang_name' => $this->input->post("hang_name"),
        );
        $kiemtra = $this->mod_hang->suahangsanxuat($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
	function delete() {
		$id = $this->input->post('id');
		$this->mod_hang->_update($id, array('hang_status' => '2'));
		echo json_encode(array('code' => '100'));
	}

}
