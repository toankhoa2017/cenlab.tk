<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Baogia extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_baogia');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_baogia');
        $this->load->model('mod_yeucau');
        $this->load->model('vattu/mod_nhacungcap');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('security'); 
    }

    function index() {
        if ($this->session->userdata('status') == 1) {
            $status = 1;
            $this->session->unset_userdata('status');
        } else if ($this->session->userdata('status') == 2) {
            $status = 2;
            $this->session->unset_userdata('status');
        } else {
            $status = 3;
        }
        $this->parser->assign('status', $status);
        $this->parser->parse('baogia/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_baogia->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url('muasam/baogia/giatien/' . $item->denghi_id) . '">' . $item->denghi_title . '</a>';
            $row[] = $item->denghi_date;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_goi,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $this->curl->create($this->_api['nhansu'] . 'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $item->nhansu_nhan,
            ));
            $nhansu = json_decode($this->curl->execute());
            $row[] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $trangthai = false;
            $hientai = $this->mod_baogia->hientai($item->denghi_id);
            $vesion = $hientai->denghi_vesion;
            $denghi_id = $hientai->denghi_id;
            while ($trangthai == false) {
                $dulieu = $this->mod_baogia->check($denghi_id);
                if ($dulieu == true) {
                    $vesion = $dulieu->denghi_vesion;
                    $denghi_id = $dulieu->denghi_id;
                } else {
                    $trangthai = true;
                }
            }
            $denghi = $this->mod_yeucau->denghi($item->denghi_id);
            if($denghi->denghi_vesion=='3'&&$denghi->denghi_approve=='2'){ $status=$denghi->denghi_approve;$moi = $ngonngu['status_moi'];}elseif($denghi->denghi_vesion=='3'){$status=$denghi->denghi_approve;$moi = $ngonngu['status_choduyet'];} else { $status=$item->denghi_approve; $moi=$ngonngu['status_moi'];}
            if($item->denghi_success == 2) {
                $status=1;
                $xong = $ngonngu['status_hoanthanh'];
            }else{
                $xong = $ngonngu['status_daduyet'];
            }
            switch ($status){
                case '0':
                    $row[] = '<span class="label label-sm label-primary">'.$moi.'</span>';
                    break;
                case '1':
                    $row[] = '<span class="label label-sm label-success">'.$xong.'</span>';
                    break;
                case '2':
                    $row[] = '<span class="label label-sm label-danger">'.$ngonngu['status_xemxetlai'].'</span>';
                    break;
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_baogia->count_all(),
            "recordsFiltered" => $this->mod_baogia->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function giatien($id) {
        $trangthai = false;
        $hientai = $this->mod_baogia->hientai($id);
        $vesion = $hientai->denghi_vesion;
        $denghi_id = $id;
        while ($trangthai == false) {
            $dulieu = $this->mod_baogia->check($denghi_id);
            if ($dulieu == true) {
                $vesion = $dulieu->denghi_vesion;
                $denghi_id = $dulieu->denghi_id;
            } else {
                $trangthai = true;
            }
        }
        $this->parser->assign('quytrinh', $vesion);

        $this->curl->create($this->_api['nhansu'] . 'all_nhansu');
        $this->curl->post();
        $nhansu = json_decode($this->curl->execute());
        $danhsach_nhansu = array();
        foreach ($nhansu->list as $row) {
            $danhsach_nhansu[$row->nhansu_id] = $row->nhansu_lastname . ' ' . $row->nhansu_firstname;
        }
        $this->parser->assign('nhansu', $danhsach_nhansu);

        $danhsach_nhacungcap = array();
        $nhacungcap = $this->mod_yeucau->danhsach_nhacungcap();
        foreach ($nhacungcap as $row) {
            $danhsach_nhacungcap[$row->ncc_id] = $row->ncc_name;
        }
        $this->parser->assign('nhacungcap', $danhsach_nhacungcap);

        $denghi = $this->mod_yeucau->denghi($id);
        if($denghi->denghi_approve==1||$denghi->denghi_approve==0){
            $denghi_detail = $this->mod_yeucau->denghi_detail($id);
        }else{
            $denghi_detail = $this->mod_baogia->denghi_detail($id);
        }
        $ds_file = $this->mod_baogia->_getFilebaogia($id);
        if($ds_file != '') {
            $this->parser->assign('danhsach_file', $ds_file);
        }
        $this->parser->assign('denghi', $denghi);
        $this->parser->assign('denghi_detail', $denghi_detail);
        $denghi_approve = $this->mod_yeucau->denghi_approve($denghi->denghi_id);
        if(count($denghi_approve)==0){
            $denghi_approve="";
        }
        $this->parser->assign('denghi_approve', $denghi_approve);
        $this->parser->parse('baogia/detail');
       // print_r($denghi_detail);
    }
    
    function upfilebaogia(){
        $id_denghi = $this->input->get('id');
        $config["upload_path"] = './_uploads/baogia';
        $config["allowed_types"] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        for($count = 0; $count<count($_FILES["files"]["name"]); $count++)
        {
            $filename = str_replace(" ", "", $_FILES["files"]["name"][$count]);
            $_FILES["file"]["name"] = $filename;
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
            if($this->upload->do_upload('file'))
            {
                $id_file = $this->mod_nhacungcap->_createfile(array('path_file' => $filename));
                $this->mod_baogia->_insertbaogia(array('denghi_id' => $id_denghi, 'id_file' => $id_file));
            }
        }
        echo 'ok';
    }
    function download() {
        $id_denghi = $this->input->get('id_denghi');
        $id_file = $this->input->get('id_file');
        $fileInfo = $this->mod_baogia->_getFilebaogia($id_denghi, $id_file);
        $this->load->helper('download');
        foreach ($fileInfo as $file) {
            $filedown = './_uploads/baogia/'.$file['path_file'];
        }
        force_download($filedown, NULL);
    }
    function deletebaogia() {
        $id_denghi = $this->input->post('id_denghi');
        $id_file = $this->input->post('id_file');
        $fileInfo = $this->mod_baogia->_getFilebaogia($id_denghi, $id_file);
        foreach ($fileInfo as $file) {
            $file_del = './_uploads/baogia/'.$file['path_file'];
        }
        unlink($file_del);
        $this->mod_baogia->_deletebaogia($id_denghi, $id_file);
    }
    function ListNccSP() {
        $id_sp = $this->input->post('id_sp');
        $ncc_id = $this->input->post('ncc_id');
        $list = $this->mod_baogia->_getNCCSanPham($id_sp);
        $output = '<option value="0">Chọn nhà cung cấp</option>';
        foreach ($list as $ncc) {
            if($ncc_id != 0 && $ncc['ncc_id'] == $ncc_id) {
                $output.= '<option selected value="'.$ncc['ncc_id'].'">'.$ncc['ncc_name'].'</option>';
            }
            else {
                $output.= '<option value="'.$ncc['ncc_id'].'">'.$ncc['ncc_name'].'</option>';
            }
        }
        echo $output;
    }
}
