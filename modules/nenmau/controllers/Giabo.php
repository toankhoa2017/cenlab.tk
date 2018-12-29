<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Giabo extends MY_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('giabo');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_giabo');
    }

    function index() {
        $giabo_code = $this->mod_giabo->info_giabo($this->input->get('gb'));
        $info_nenmau = $this->mod_giabo->info_nenmau($this->input->get('nm'));
        $this->parser->assign("nenmau", $info_nenmau);
        $this->parser->assign("giabo_id", $this->input->get('gb'));
        $this->parser->assign("giabo_code", $giabo_code);
        $this->parser->parse('giabo/list');
    }

    function ajax_list() {
        $giabo_id = $this->input->post('giabo_id');
        $list = $this->mod_giabo->danhsach_giabo($giabo_id);
        $data = array();
        foreach ($list as $row) {
            $hang = array();
            $hang[] = $row->package_code;
            $hang[] = $row->nenmau_name;
            $hang[] = $row->chitieu_name;
            $hang[] = number_format($row->price);
            $button = '';
            $button .= ' <button class="btn btn-xs btn-danger" onclick="_xoa_giabo(\'' . $row->package_code . '\')"><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>';
            $hang[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $hang;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_giabo->count_all($id_nenmau),
            "recordsFiltered" => $this->mod_giabo->count_filtered($id_nenmau),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_giabo->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoa_bogia() {
        $giabo_id = $this->input->post("giabo_id");
        $package_code = $this->input->post("package_code");
        $dulieu = $this->mod_giabo->xoa_bogia($giabo_id, $package_code);
        ($dulieu == true) ? $kq = '1' : $kq = '2';
        echo $kq;
    }

    function add_bogia() {
        $giabo_id = $this->input->post("giabo_id");
        $package_code = $this->input->post("package_code");
        $this->mod_giabo->xoahet($giabo_id);
        for ($i = 0; $i < count($package_code); $i++) {
            $this->mod_giabo->addgiabo($giabo_id, $package_code[$i]);
        }
        echo '1';
    }

    function load_bogia() {
        $dongia = $this->mod_giabo->dongia($this->input->post('nenmau_id'));
        $danhsachchon = $this->mod_giabo->dachon($this->input->post('giabo_id'));
        $mangchon = array();
        foreach ($danhsachchon as $key1) {
            $mangchon[] = $key1->package_code;
        }
        foreach ($dongia as $row) {
            if (in_array($row->package_code, $mangchon)) {
                $check = "selected";
            } else {
                $check = "";
            };
            echo '<option ' . $check . ' value="' . $row->package_code . '">' . $row->package_code . '</option>';
        }
    }

}
