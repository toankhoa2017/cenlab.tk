<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sanpham extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('sanpham');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_sanpham');
    }

    function index() {
        $loaisanpham = array();
        $ds_loai = $this->mod_sanpham->danhsach_loai();
        foreach($ds_loai as $row){
            $loaisanpham[$row->loai_id] = $row->loai_symbol." (".$row->loai_name.")";
        }
        $this->parser->assign('loaisanpham',$loaisanpham);
        $this->parser->parse('sanpham/list');
    }
    
    function ajax_list() {
        $thaotac = $this->lang->getLang();
        $loai_id = $this->input->post('loai_id');
        $list = $this->mod_sanpham->get_datatables($loai_id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->loai_symbol."_".$item->sp_code;
            $row[] = $item->sp_name ;
            $row[] = $item->sp_mota ;
            $code = explode('_', $item->sp_code);
            if(count($code)>1){
                $code = $code[1];
            }else{
                $code = "";
            }
            $button = '';
            $button .= ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->sp_id . ',\'' . $code . '\',\'' . $item->sp_name . '\',\'' . $item->sp_mota . '\',\'' . $item->loai_id . '\')" data-toggle="tooltip" title="'.$thaotac['tooltip_update'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>';
            $button .= ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->sp_id . ')" data-toggle="tooltip" title="'.$thaotac['tooltip_delete'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_sanpham->count_all($loai_id),
            "recordsFiltered" => $this->mod_sanpham->count_filtered($loai_id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_sanpham->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    
    function suasanpham(){
        $check_code = $this->mod_sanpham->check_code($this->input->post("sp_id"));
        $code = explode('_', $check_code->sp_code);
        if($this->input->post("sp_code")!=""){
            $ma_code = $code[0].'_'.$this->input->post("sp_code");
        }else{
            $ma_code = $code[0];
        }
        
        $data = array(
            'sp_id' => $this->input->post("sp_id"),
            'sp_code' => $ma_code,
            'sp_name' => $this->input->post("sp_name"),
            'sp_mota' => $this->input->post("sp_mota"),
            'loai_id' => $this->input->post("loai_id"),
        );
        $kiemtra = $this->mod_sanpham->suasanpham($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    
    function xoasanpham(){
        $sp_id =  $this->input->post("sp_id");
        $kiemtra = $this->mod_sanpham->xoasanpham($sp_id);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
