<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Donvitinh extends ADMIN_Controller {

    private $privcheck;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('list_donvitinh');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_PTN_DONVITINH];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_donvitinh');
    }

    function index() {
        if (!$this->privcheck['read'])
            redirect(site_url() . 'admin/denied?w=read');
        $this->parser->parse('donvitinh/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_donvitinh->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->donvitinh_name;
            $button = '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->donvitinh_id . ',\'' . $item->donvitinh_name . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->donvitinh_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_donvitinh->count_all(),
            "recordsFiltered" => $this->mod_donvitinh->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_donvitinh->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoadonvitinh() {
        $id_donvitinh = $this->input->post("iddonvitinhxoa");
        $this->mod_donvitinh->xoadonvitinh($id_donvitinh);
        echo "1";
    }

    function suadonvitinh() {
        $id_donvitinh = $this->input->post("iddonvitinhsua");
        $name_donvitinh = $this->input->post("namedonvitinhsua");
        $name_truocthaydoi = $this->input->post("tendonvitinhtruocthaydoi");
        $data = array(
            'donvitinh_id' => $id_donvitinh,
            'donvitinh_name' => $name_donvitinh,
        );
        $kiemtra = $this->mod_donvitinh->suadonvitinh($data, $name_truocthaydoi);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
