<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends ADMIN_Controller {

    private $privchitieu;
    private $privdinhgia;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('chitieu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->load->model('mod_chat');
        $this->load->model('mod_chitieu');
    }
    private function checkChitieuChatExist($dongia_id, $chat_id){
        $con = array(
            'dongia_id' => $dongia_id,
            'chat_id' => $chat_id,
        );
        $result = $this->db->select("dongia_id as chitieu_id, chat_id, capacity, val_min, val_max")->from("mau_chitieu_chat")->where($con)->get();
        return $result->num_rows();
    }
    private function checkCongnhanChatExist($congnhan_id, $chat_id){
        $con = array(
            'congnhan_id' => $congnhan_id,
            'chat_id' => $chat_id,
        );
        $result = $this->db->select("*")->from("mau_congnhan_chat")->where($con)->get();
        return $result->num_rows();
    }
    function index() {
        $noicongnhan = array();
        $noicongnhans = $this->mod_chitieu->congnhan();
        foreach ($noicongnhans as $row) {
            $noicongnhan[$row->congnhan_id] = $row->congnhan_name;
        }
        $dongiaChats = $this->mod_chat->getGiaByDongia($this->input->get('package'));
        $dongiaInfo = $this->mod_chat->getDonGiaInfo($this->input->get('package'));
        $chatGias = array();
        foreach ($dongiaChats as $dongia){
            $chatGias[] = $dongia["gia_price"];
        }
        $num_chat = $this->mod_chat->count_all($this->input->get('package'));
        $this->parser->assign('chatGias', $chatGias);
        $this->parser->assign('num_chat', $num_chat);
        $this->parser->assign('noicongnhan', $noicongnhan);
        $this->parser->assign('dongiaInfo', $dongiaInfo);
        $this->parser->assign('package', $this->input->get('package'));
        $this->parser->parse('chat/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $package = $this->input->get('package');
        $list = $this->mod_chat->get_datatables($package);
        $data = array();
        $stt = 0;
        foreach ($list as $row) {
            $hang = array();
            $hang[] = ++$stt;
            $hang[] = $row->chat_name.'<br />'.$row->chat_name_eng;
            $giatri = $this->mod_chat->get_congnhan($row->chat_id);
            $giatri1 = "";
            $id_giatri = "";
            if ($giatri == true) {
                $tong = count($giatri);
                $dem = 0;
                foreach ($giatri as $row1) {
                    $dem++;
                    if ($dem == $tong) {
                        $giatri1 = $giatri1 . $row1->congnhan_sign;
                        $id_giatri = $id_giatri . $row1->congnhan_id;
                    } else {
                        $giatri1 = $giatri1 . $row1->congnhan_sign . ",";
                        $id_giatri = $id_giatri . $row1->congnhan_id . "/";
                    }
                }
            } else {
                $giatri1 = "-/-";
                $id_giatri = 0;
            }
            if ($row->capacity == "1") {
                $text_Min = "LOD";
                $text_Max = "LOQ";
            } else {
                $text_Min = "MIN";
                $text_Max = "MAX";
            }
            $hang[] = '<ul>
                            <li>'.$ngonngu['noicongnhan'].': ' . $giatri1 . '</li>
                            <li>' . $text_Min . ': ' . $row->val_min . '</li>
                            <li>' . $text_Max . ': ' . $row->val_max . '</li>
                            <li> Dung Sai: ' . $row->dung_sai . '</li>
                      <ul>';
            $button = '';
            $button .= ($this->privchitieu['update']) ? ' <button class="btn btn-xs btn-info" data-toggle="tooltip" title="'.$ngonngu['tooltip_suachat'].'" onclick="_sua_chat(' . $row->chat_id . ',' . $row->chitieu_id . ',\'' . $row->chat_name . '\',\'' . $row->chat_name_eng . '\',\'' . $row->chat_describe . '\',\'' . $id_giatri . '\',\'' . $row->val_min . '\',\'' . $row->val_max . '\',\'' . $row->capacity . '\',\'' . $row->dung_sai . '\')"><i class="ace-icon fa fa-pencil bigger-120" ></i></button>' : '';
            $button .= ($this->privchitieu['delete']) ? ' <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoachat'].'" onclick="_xoa_chat(' . $row->chat_id . ',' . $row->chitieu_id . ')"><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>' : '';
            $hang[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $hang;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_chat->count_all($package),
            "recordsFiltered" => $this->mod_chat->count_filtered($package),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function goiy_chat() {
        $tukhoa = $this->input->post('key');
        $dulieu = $this->mod_chat->goiy_chat($tukhoa);
        foreach ($dulieu as $row) {
            $output[] = array(
                "label" => $row->chat_name,
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }
    
    function them_chat() {
        $giatri = $this->input->post();
        if ($giatri['chat_id'] == "" || $giatri['chat_id'] == "0") {
            $data = array(
                'chat_name' => $giatri['chat_name'],
                'chat_describe' => $giatri['chat_mota'],
                'chat_name_eng' => $giatri['chat_name_eng'],
            );
            $chat_id = $this->mod_chat->them_chat($data);
        }else{
            $chat_id = $giatri["chat_id"];
        }
        
        if ($giatri['congnhan'] != "null") {
            for ($i = 0; $i < count($giatri['congnhan']); $i++) {
                $dulieu_congnhan = array(
                    'congnhan_id' => ($giatri['congnhan'][$i]),
                    'chat_id' => $chat_id,
                );
                if(!$this->checkCongnhanChatExist($giatri['congnhan'][$i], $chat_id))
                    $this->mod_chat->them_congnhan($dulieu_congnhan);
            }
        }
        if(!$this->checkChitieuChatExist($giatri['chitieu_id'], $chat_id)){
            $dulieu1 = array(
                'dongia_id' => $giatri['chitieu_id'],
                'chat_id' => $chat_id,
                'val_min' => $giatri['lod'] == "" ? NULL : $giatri['lod'],
                'val_max' => $giatri['loq'] == "" ? NULL : $giatri['loq'],
                'capacity' => $giatri['capacity'],
                'dung_sai' => $giatri['dung_sai']
            );
            $dulieu = $this->mod_chat->them_chitieu_chat($dulieu1);
        }else{
            $dulieu = 1;
        }
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function xoa_chat() {
        $chat_id = $this->input->post('chat_id');
        $chitieu_id = $this->input->post('chitieu_id');
        $nenmau_id = $this->input->post('nenmau_id');
        $this->mod_chat->xoa_chat($chat_id,$chitieu_id,$nenmau_id);
        echo "1";
    }

    function sua_chat() {
        $giatri = $this->input->post();
        $data = array(
            'chat_id' => $giatri['chat_id'],
            'chat_name' => $giatri['chat_name'],
            'chat_describe' => $giatri['chat_mota'],
            'chat_name_eng' => $giatri['chat_name_eng'],
        );
        $value = array(
            'chat_id' => $giatri['chat_id'],
            'dongia_id' => $giatri['chitieu_id'],
            'val_max' => $giatri['loq'] == "" ? NULL : $giatri['loq'],
            'val_min' => $giatri['lod'] == "" ? NULL : $giatri['lod'],
            'capacity' => $giatri['capacity'],
            'dung_sai' => $giatri['dung_sai']
        );
        $this->mod_chat->mau_chitieu_chat($value);
        $this->mod_chat->sua_chat($data);
        $this->mod_chat->sua_xoa_quanhe_congnhan($giatri['chat_id']);
        if ($giatri['congnhan'] != "null") {
            for ($i = 0; $i < count($giatri['congnhan']); $i++) {
                $this->mod_chat->sua_quanhe_congnhan($giatri['chat_id'], $giatri['congnhan'][$i]);
            }
        }
        echo "1";
    }
    
    function getGia(){
        $item = $this->input->get();
        $result = $this->mod_chat->getGiaByDongia($item["dongia_id"]);
    }
            
    function setgia(){
        $giatri = $this->input->post();
        $datas = [];
        foreach ($giatri['gia'] as $key=>$gia){
            $giachat = array(
                'gia_order' => $key + 1,
                'gia_price' => $gia,
                'bo_id' => $giatri['dongia']
            );
            $datas[] = $giachat;
        }
        $this->mod_chat->setgia($datas);
        echo "1";
    }

}
