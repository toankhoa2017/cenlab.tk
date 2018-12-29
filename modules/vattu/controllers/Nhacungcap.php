<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nhacungcap extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('nhacungcap');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_nhacungcap');
	$this->load->model('general/mod_file');
        $this->load->model('mod_loai');
        $this->load->model('mod_sanpham');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('security');    
    }
    function index() {
        $this->parser->parse('nhacungcap/list');
    }
    function ajax_list() {
        $thaotac = $this->lang->getLang();
        $list = $this->mod_nhacungcap->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="nccname" data-id="'.$item->ncc_id.'"><a href="'.site_url().'vattu/nhacungcap/detail?id='.$item->ncc_id.'&name='.$item->ncc_name.'">'.$item->ncc_name.'</a></div>';
            $row[] = $item->ncc_address;
            $button = '';
            $button .= '<button class="btn btn-xs btn-warning profile" data-toggle="tooltip" title="Profile"><i class="ace-icon fa fa-user bigger-110"></i></button> <button class="btn btn-xs btn-info" onclick="_sua(' . $item->ncc_id . ',\'' . $item->ncc_name . '\',\'' . $item->ncc_address . '\',\'' . $item->ncc_hoso . '\',\'' . $file_name . '\')" data-toggle="tooltip" title="'.$thaotac['tooltip_update'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button> <button class="btn btn-xs btn-danger delete" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_nhacungcap->count_all(),
            "recordsFiltered" => $this->mod_nhacungcap->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
	
    function ajax_add() {
        $items = $this->input->post();       
        $kiemtra = $this->mod_nhacungcap->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function sualoaisanpham() {
        $data = array(
            'loai_id' => $this->input->post("loai_id"),
            'loai_name' => $this->input->post("loai_name"),
            'loai_symbol' => $this->input->post("loai_symbol"),
        );
        $kiemtra = $this->mod_nhacungcap->sualoaisanpham($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    
    function suanhacungcap(){
        $data = array(
            'ncc_id' => $this->input->post("ncc_id"),
            'ncc_name' => $this->input->post("ncc_name"),
            'ncc_address' => $this->input->post("ncc_address")
        );
        $kiemtra = $this->mod_nhacungcap->suanhacungcap($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function update() {
        $id = $this->input->post('id');
        $this->mod_nhacungcap->suanhacungcap(array('ncc_id' => $id, 'ncc_status' => '2'));
        echo json_encode(array('err_code' => '100'));
    }
    function profile() {
        $id = $this->input->get('id');
        $this->parser->assign('id', $id);
        $this->parser->assign('name', $this->input->get('name'));
        $this->parser->assign('profile', $this->mod_nhacungcap->_getprofile($id));
        $this->parser->parse('nhacungcap/profile');
    }
    function addprofile() {
        $file_element_name = 'files';
        $config["upload_path"] = './_uploads/nhacungcap';
        $config["allowed_types"] = '*';
        $id_file = '';
        if($_POST["id_profile"] == '') {
            if (isset($_FILES['file']['name'])) {
                $filename = str_replace(" ", "", $_FILES["file"]["name"]);
                $_FILES["file"]["name"] = $filename;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file')) {
                    $id_file = $this->mod_nhacungcap->_createfile(array('path_file' => $filename));
                }
            }
            $kt = $this->mod_nhacungcap->_createprofile(array('profile_name' => $_POST["name_profile"], 'id_ncc' => $_POST["id_ncc"], 'file_id' => $id_file));
            if ($kt == TRUE) {
                echo json_encode(array('code' => '100', 'mess' => 'Thanh công'));
            }
            else {
                echo json_encode(array('code' => '200', 'mess' => 'Có lỗi'));
            }
        }
        else {
            if (isset($_FILES['file']['name'])) {
                $id_file = $_POST["id_file"];
                $fileInfo = $this->mod_nhacungcap->_getFile($id_file);
                foreach ($fileInfo as $file) {
                    $file_del = './_uploads/nhacungcap/'.$file['path_file'];
                }
                unlink($file_del);
                $this->mod_nhacungcap->_delFile($id_file);
                $filename = str_replace(" ", "", $_FILES["file"]["name"]);
                $_FILES["file"]["name"] = $filename;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file')) {
                    $id_file = $this->mod_nhacungcap->_createfile(array('path_file' => $filename));
                }
                $this->mod_nhacungcap->_updateprofile($_POST["id_profile"], array('profile_name' => $_POST["name_profile"], 'file_id' => $id_file));
                echo json_encode(array('code' => '100', 'mess' => 'Thanh công'));
            }
            else {
                $this->mod_nhacungcap->_updateprofile($_POST["id_profile"], array('profile_name' => $_POST["name_profile"]));
                echo json_encode(array('code' => '100', 'mess' => 'Thanh công'));
            }
        }
    }
    function getfile() {
        $id  = $this->input->get('id');
        $fileInfo = $this->mod_nhacungcap->_getFile($id);
        $this->load->helper('download');
        foreach ($fileInfo as $file) {
            $file_down = './_uploads/nhacungcap/'.$file['path_file'];
        }
        force_download($file_down, NULL);

    }
    
    function delprofile(){
        $this->mod_nhacungcap->_updateprofile($this->input->post('id'), array('profile_status' => '2'));
        echo json_encode(array('code' => '100'));
    }
    function detail() {
        $id = $this->input->get('id');
        $this->parser->assign('id', $id);
        $this->parser->assign('name', $this->input->get('name'));
        $this->parser->assign('loaisp', $this->mod_loai->get_datatables());
        $this->parser->parse('nhacungcap/detail');
    }
    function listsp() {
        $id_loai = $this->input->post('id_loai');
        $id_ncc = $this->input->post('id_ncc');
        $list = $this->mod_nhacungcap->_Getlistsp($id_loai, $id_ncc);
        
        $output = '<option value="0">Chọn Sản Phẩm</option>';
        foreach ($list as $sp) {
            $output.= '<option value="'.$sp['sp_id'].'">'.$sp['sp_name'].'</option>';
        }
        echo $output;
    }
    
    function addspncc() {
        $this->mod_nhacungcap->_InsSPNCC(array('ncc_id' => $this->input->post('id_ncc'), 'loai_id' => $this->input->post('id_loai'), 'sp_id' => $this->input->post('sanpham')));
        echo json_encode(array('code' => '100'));
    }
    
    function ajax_listspncc() {
        $id = $this->input->get('id');
        $list = $this->mod_nhacungcap->get_datatables_spncc($id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $item->loai_name;
            $row[] = $item->sp_name;
            $button = '';
            $button .= '<button class="btn btn-xs btn-danger" onclick="_xoa('.$item->ncc_id.','.$item->sp_id.')" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_nhacungcap->count_all_spncc($id),
            "recordsFiltered" => $this->mod_nhacungcap->count_filtered_spncc($id),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function delspncc() {
        $this->mod_nhacungcap->_DelSPNCC(array('ncc_id' => $this->input->post('id_ncc'), 'loai_id' => $this->input->post('id_sp')));
        echo json_encode(array('code' => '100'));
    }
}
