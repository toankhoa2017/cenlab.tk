<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kho extends ADMIN_Controller {
    private $privcheck;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('kho');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_LUU_KHO];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_kho');
    }

    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $parent = $this->input->get('id');
        if($parent==""){$parent=0;}else{
            $info_kho = $this->mod_kho->info_kho($parent);
        };
        $donvi = $this->session->userdata('ssAdminDonvi');
        $this->parser->assign('donvi_id', $donvi);
        $this->parser->assign('kho_id', $parent);
        $ref = $this->mod_kho->_capbac_kho($info_kho[0]->kho_ref,$info_kho[0]->kho_id,$info_kho[0]->kho_name);
        $this->parser->assign('ref', $ref);
        $this->parser->assign('kho_info', $info_kho);
        $this->parser->parse('kho/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $parent = $this->input->post('id');
        if ($parent) $this->mod_kho->parent = $parent;
        $list = $this->mod_kho->get_datatables($this->session->userdata('ssAdminDonvi'));
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            if ($item->kho_max_level < 1) {
                $row[] = $item->kho_name;
            }else{
                $row[] = '<a href="'.base_url('luumau/kho?id='.$item->kho_id).'" >'.$item->kho_name.'</a>';
            }
            $row[] = $item->kho_mota;
            $row[] = $item->kho_loai==0 ? "Không Là Thiết Bị" : "Thiết Bị ".$item->thietbi_id ;
            $this->curl->create($this->_api['nhansu'].'get_donvi');
            $this->curl->post(array(
                'donvi_id' => $item->donvi_id,
            ));
            $donvi = json_decode($this->curl->execute());
            $row[] = $donvi->donvi_name;
            $button = '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->kho_id . ',\'' . $item->kho_name . '\',\'' . $item->kho_mota . '\',\'' . $item->kho_loai . '\',\'' . $item->thietbi_id . '\',\'' . $item->donvi_id . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->kho_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_kho->count_all($this->session->userdata('ssAdminDonvi')),
            "recordsFiltered" => $this->mod_kho->count_filtered($this->session->userdata('ssAdminDonvi')),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        if (!$this->privcheck['write']) redirect(site_url() . 'admin/denied?w=write');
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_kho->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
            $catchuoi = explode("-", $items['ref']);
            $items['kho_level'] = count($catchuoi)-1;
            $kho_max_level = $this->mod_kho->get_kho_max_level($catchuoi[1]);
            if($kho_max_level!="0"){
                $items['kho_max_level'] = (int)$kho_max_level - (int)$items['kho_level'] + 1;
            }else{
                $items['kho_max_level'] = 0;
            }
        } else {
            $items['ref'] = '-';
            $items['kho_level'] = '1';
        }
        $kiemtra = $this->mod_kho->_create($items);
        if ($kiemtra == true) {
            echo '1';
        } else {
            echo '2';
        }
    }

    function xoakho() {
        if (!$this->privcheck['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $kho_id = $this->input->post("kho_id");
        $this->mod_kho->xoakho($kho_id);
        echo "1";
    }
    
    function update_donvi_kho($danhsach, $id_parent, $donvi_id) {
        $cate_child = array();
        foreach ($danhsach as $key => $item) {
            if ($item->kho_idparent == $id_parent) {
                $cate_child[] = $item;
                $this->mod_kho->update_donvi_kho($donvi_id,$item->kho_id);
                unset($danhsach[$key]);
            }
        }
        if ($cate_child) {
            foreach ($cate_child as $key => $item) {
                $this->mod_kho->update_donvi_kho($donvi_id,$item->kho_id);
                $this->update_donvi_kho($danhsach, $item->kho_id, $donvi_id);
            }
        }
    }
    
    function suakho() {
        if (!$this->privcheck['update']) redirect(site_url() . 'admin/denied?w=update');
        $value = $this->input->post();
        $danhsach = $this->mod_kho->danhsach();
        $this->update_donvi_kho($danhsach, $value['kho_id'], $value['donvi_id']);
        $kiemtra = $this->mod_kho->suakho($value);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
}
