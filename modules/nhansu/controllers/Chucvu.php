<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Chucvu extends ADMIN_Controller {
    private $privcheck;
    private $dsreview;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('chucvu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_TOCHUC_CHUCVU];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_chucvu');
    }
    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $id = $this->input->get('id');
        $this->parser->assign('id', $id ? $id : 0);
        $this->mod_chucvu->_levelUps($id, 0);
        $this->parser->assign('listUps', $this->mod_chucvu->listUps);
        if (isset($id) && $id != 0) {
            $this->parser->assign('trove', 'trove');
        } else {
            $this->parser->assign('trove', 'khongtrove');
        }
        $this->parser->parse('chucvu/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $parent = $this->input->get('id');
        if ($parent)
            $this->mod_chucvu->parent = $parent;
        $list = $this->mod_chucvu->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $chucvu) {
            $no++;
            $row = array();
            $row[] = "<a href='" . site_url() . "nhansu/chucvu?id=" . $chucvu->chucvu_id . "'>" . $chucvu->chucvu_ten . "</a>";
            $button = '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-minier btn-info" onclick="_sua(' . $chucvu->chucvu_id . ',\'' . $chucvu->chucvu_ten . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-110"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-minier btn-danger" onclick="_xoa(' . $chucvu->chucvu_id . ',\'' . $chucvu->chucvu_ten . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-110"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_chucvu->count_all(),
            "recordsFiltered" => $this->mod_chucvu->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    function ajax_add() {
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_chucvu->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
            $items['chucvu_level'] = count(explode("-", $items['ref']))-1;
        }else{
            $items['ref'] = '-';
            $items['chucvu_level'] = '1';
        }
        $kiemtra = $this->mod_chucvu->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    function ajax_edit() {
        $id_chucvu = $this->input->post("idchucvusua");
        $name_chucvu = $this->input->post("namechucvusua");
        $data = array(
            'chucvu_id' => $id_chucvu,
            'chucvu_ten' => $name_chucvu
        );
        $kiemtra = $this->mod_chucvu->_update($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function delete() {
        if (!$this->privcheck['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $idchucvu = $this->input->post("idchucvu");
        $dulieu = $this->mod_chucvu->_delete($idchucvu);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function dschucvu() {
        $id = $this->input->post('id');
        $data = $this->mod_chucvu->_getdanhsachcvu($id);
        $output = '<option value="">Chọn Chức Vụ</option>';
        foreach($data as $cv) {
            $output.='<option value="'.$cv['chucvu_id'].'">'.$cv['chucvu_ten'].'</option>';
        }
        echo json_encode(array('a' => $output));
    }
    function insertDV_CV() {
        $data = $this->input->post();
        $this->mod_chucvu->_InsertDV_CV(array('donvi_id' => $data['id_donvi'], 'chucvu_id' => $data['id_chucvu'], 'soluong' => $data['soluong']));
        echo json_encode(array('code' => '100'));
    }
    function updataDV_CV() {
        $data = $this->input->post();
        $this->mod_chucvu->_UpdateDV_CV($data['id_donvi'],$data['id_chucvu'], array('soluong' => $data['soluong']));
        echo json_encode(array('code' => '100', 'mess' => 'ok'));
    }
    function delcv() {
        $data = $this->input->post();
        $this->mod_chucvu->_DeleteDV_CV($data['id_donvi'], $data['id_chucvu']);
        echo json_encode(array('code' => '100', 'mess' => 'ok'));
    }
}
