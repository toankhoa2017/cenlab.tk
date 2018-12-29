<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kythuat extends ADMIN_Controller {

    private $privcheck;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('kythuat');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_MOD_KYTHUAT];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_kythuat');
    }

    function index() {
        //if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $this->parser->parse('kythuat/list');
    }

    function ajax_list() {
        $list = $this->mod_kythuat->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->kythuat_name;
            $row[] = $item->kythuat_name_eng;
            $row[] = $item->kythuat_describe;
            $button = '';
            $button .= (1) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->kythuat_id . ',\'' . $item->kythuat_name . '\',\'' . $item->kythuat_describe . '\',\'' . $item->kythuat_name_eng . '\')" data-toggle="tooltip" title="Sửa"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= (1) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->kythuat_id . ')" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_kythuat->count_all(),
            "recordsFiltered" => $this->mod_kythuat->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_kythuat->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoakythuat() {
        $id_kythuat = $this->input->post("idkythuatxoa");
        $this->mod_kythuat->xoakythuat($id_kythuat);
        echo "1";
    }

    function suakythuat() {
        $id_kythuat = $this->input->post("idkythuatsua");
        $name_kythuat = $this->input->post("namekythuatsua");
        $mota_kythuat = $this->input->post("motakythuatsua");
        $name_truocthaydoi = $this->input->post("tenkythuattruocthaydoi");
        $name_eng = $this->input->post("name_eng");
        $data = array(
            'kythuat_id' => $id_kythuat,
            'kythuat_name' => $name_kythuat,
            'kythuat_describe' => $mota_kythuat,
            'kythuat_name_eng' => $name_eng
        );
        $kiemtra = $this->mod_kythuat->suakythuat($data, $name_truocthaydoi);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
