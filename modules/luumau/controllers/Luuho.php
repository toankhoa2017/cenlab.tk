<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Luuho extends ADMIN_Controller {

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('luuho');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->model('mod_luuho');
        $this->load->model('mod_mau');
    }

    function index() {
        $this->parser->parse('luuho/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_luuho->get_datatables($this->session->userdata('ssAdminDonvi'));
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $row = array();
            $this->curl->create($this->_api['nhanmau'] . 'getMau');
            $this->curl->post(array(
                'id' =>  $item->mau_id
            ));
            $mau = json_decode($this->curl->execute());
            $luumau = $this->mod_mau->info_luumau($item->luumau_id)[0];
            $row[] = "<a href='" . site_url() . "luumau/luuho/detail/" . $mau->mau->id . "'>" .$mau->mau->code. "</a>";
            $row[] = $mau->mau->name;
            $row[] = $mau->mau->dieukienluu;
            /*switch () {
                case 'N':
                    $row[] = '<span class="blue">'.$ngonngu['status_chualuu'].'</span>';
                    break;
                case 'Y':
                    $row[] = '<span class="green">'.$ngonngu['status_daluu'].'</span>';
                    break;
                case 'H':
                    $row[] = '<span class="red">'.$ngonngu['status_huy'].'</span>';
                    break;
            }*/
            if($luumau->kho_id){
                $row[] = '<span class="green">'.$ngonngu['status_daluu'].'</span>';
            }else{
                $row[] = '<span class="blue">'.$ngonngu['status_chualuu'].'</span>';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_luuho->count_all($this->session->userdata('ssAdminDonvi')),
            "recordsFiltered" => $this->mod_luuho->count_filtered($this->session->userdata('ssAdminDonvi')),
            "data" => $data,
        );
        echo json_encode($output);
    }
    
    function detail($id) {
        $this->curl->create($this->_api['nhanmau'] . 'getMau');
        $this->curl->post(array(
            'id' => $id,
        ));
        $mau = json_decode($this->curl->execute());
        $info_luuho = $this->mod_luuho->info_luuho($id);
        $donvi_duyet = array();
        foreach ($info_luuho as $row){
            $donvi_duyet[] = $row->donvi_id;
        }
        if (!in_array($this->session->userdata('ssAdminDonvi'), $donvi_duyet)) {
            redirect(site_url() . 'luumau/luuho');
            exit;
        }
        $this->parser->assign('mau', $mau->mau);

        $dulieu = $this->mod_luuho->get_kho($this->session->userdata('ssAdminDonvi'), 0);

        $this->curl->create('http://dev.tamducjsc.info/nhansu/api/all_donvi');
        $this->curl->post(array());
        $list = json_decode($this->curl->execute());
        $donvi = array();
        foreach ($list->danhsach as $row) {
            if ($row->donvi_id != $this->session->userdata('ssAdminDonvi')) {
                $donvi[$row->donvi_id] = $row->donvi_ten;
            }
        }
        $this->parser->assign('donvi', $donvi);
        $tuluu = array();
        $tuluu[0] = "Chọn Tủ Lưu Mẫu";
        foreach ($dulieu as $row) {
            $tuluu[$row->kho_id] = $row->kho_name;
        }
        $this->parser->assign('tuluu', $tuluu);
        
        $danhsach_file = $this->mod_luuho->danhsach_file($id);
        $danhsachfile = array();
        foreach ($danhsach_file as $item) {
            $this->curl->create($this->_api['general'] . 'get_file');
            $this->curl->post(array(
                'file_id' => $item->file_id
            ));
            $file = json_decode($this->curl->execute(), TRUE);
            $danhsachfile[] = base_url($file['site_url'] . $file['file'][0]['file_path']);
        }
        if(count($danhsach_file)=='0'){
            $danhsachfile = "";
        }
        $this->parser->assign('file', $danhsachfile);
        $this->parser->parse('luuho/detail');
    }
    
    function detail_list($id, $luu) {
        $dulieu = $this->mod_luuho->info_mau_chia($id);
        $dulieu1 = array();
        foreach ($dulieu as $row) {
            $dong = array();
            $dong['luumau_id'] = $row->luumau_id;
            $dong['luumau_name'] = $row->luumau_name;
            $dong['luumau_khoiluong'] = $row->luumau_khoiluong;
            $dong['luuho_loai'] = $row->luuho_loai;
            $dong['luuho_dieukien'] = $row->luuho_dieukien;
            $dong['luumau_status'] = $row->luumau_status;
            $this->curl->create($this->_api['nhansu'].'nhansu_info');
            $this->curl->post(array(
                'nhansu_id' => $row->nhansu,
            ));
            $nhansu = json_decode($this->curl->execute());
            $dong['nhansu'] = $nhansu->nhansu[0]->nhansu_lastname . " " . $nhansu->nhansu[0]->nhansu_firstname;
            $vitri = "";
            $vi_tri = $this->mod_mau->vitri($row->kho_id);
            $catchuoi = explode("-", $vi_tri[0]->kho_ref);
            if (count($catchuoi) == 2) {
                $vitri = $vi_tri[0]->kho_name;
            } else {
                for ($i = 0; $i < count($catchuoi); $i++) {
                    if ($catchuoi[$i] != "") {
                        $info = $this->mod_mau->vitri($catchuoi[$i]);
                        if ($vitri == "") {
                            $vitri .= $info[0]->kho_name;
                        } else {
                            $vitri .= '&rightarrow;' . $info[0]->kho_name;
                        }
                    }
                }
                if ($vi_tri[0]->kho_name != "") {
                    $vitri .= '&rightarrow;' . $vi_tri[0]->kho_name;
                }
            }
            $dong['vitri'] = $vitri;
            $dulieu1[] = $dong;
        }
        $this->parser->assign('mau_luu', $luu);
        $this->parser->assign('mau_chia', $dulieu1);
        $this->parser->parse('luuho/luu');
    }

}
