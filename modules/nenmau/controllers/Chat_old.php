<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends ADMIN_Controller {

    private $privchitieu;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('chitieu');
        //End language
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->load->model('mod_chat');
    }

    function danhsach_chat() {
        $ngonngu = $this->lang->getLang();
        if (!$this->privchitieu['read']) redirect(site_url() . 'admin/denied?w=read');
        $id_chitieu = $this->input->post('chitieu_id');
        $list = $this->mod_chat->danhsach_chat($id_chitieu);
        $data = array();
        $stt = 0;
        $this->mod_chat->get_congnhan($row->chat_id);
        foreach ($list as $row) {
            $stt++;
            $hang = array();
            $hang[] = $stt;
            $hang[] = $row->chat_name . "</br>" . $row->chat_name_eng . "</br>" .$ngonngu['chat_mota']. ": " . $row->chat_describe;
            ;
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
                      <ul>';
            $button = '';
            $button .= ($this->privchitieu['update']) ? ' <button class="btn btn-xs btn-info" data-toggle="tooltip" title="'.$ngonngu['tooltip_suachat'].'" onclick="_sua_chat(' . $row->chat_id . ',' . $row->chitieu_id . ',\'' . $row->chat_name . '\',\'' . $row->chat_name_eng . '\',\'' . $row->chat_describe . '\',\'' . $id_giatri . '\',\'' . $row->val_min . '\',\'' . $row->val_max . '\',\'' . $row->capacity . '\')"><i class="ace-icon fa fa-pencil bigger-120" ></i></button>' : '';
            $button .= ($this->privchitieu['delete']) ? ' <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoachat'].'" onclick="_xoa_chat(' . $row->chat_id . ',' . $row->chitieu_id . ')"><i class="ace-icon fa fa-trash-o bigger-120" ></i></button>' : '';
            $hang[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $hang;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_chat->count_all($id_chitieu),
            "recordsFiltered" => $this->mod_chat->count_filtered($id_chitieu),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function them_chat() {
        $giatri = $this->input->post();
        $data = array(
            'chat_name' => $giatri['chat_name'],
            'chat_describe' => $giatri['chat_mota'],
            'chat_name_eng' => $giatri['chat_name_eng'],
        );
        $chat_id = $this->mod_chat->them_chat($data);
        $dulieu1 = array(
            'chitieu_id' => $giatri['chitieu_id'],
            'chat_id' => $chat_id,
            'val_min' => $giatri['lod'] == "" ? NULL : $giatri['lod'],
            'val_max' => $giatri['loq'] == "" ? NULL : $giatri['loq'],
            'capacity' => $giatri['capacity']
        );
        if ($giatri['congnhan'] != "null") {
            for ($i = 0; $i < count($giatri['congnhan']); $i++) {
                $dulieu_congnhan = array(
                    'congnhan_id' => ($giatri['congnhan'][$i]),
                    'chat_id' => $chat_id,
                );
                $this->mod_chat->them_congnhan($dulieu_congnhan);
            }
        } else {
            $dulieu_congnhan = array(
                'congnhan_id' => '',
                'chat_id' => $chat_id,
            );
            $this->mod_chat->them_congnhan($dulieu_congnhan);
        }
        $dulieu = $this->mod_chat->them_chitieu_chat($dulieu1);
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
            'chitieu_id' => $giatri['chitieu_id'],
            'val_max' => $giatri['loq'] == "" ? NULL : $giatri['loq'],
            'val_min' => $giatri['lod'] == "" ? NULL : $giatri['lod'],
            'capacity' => $giatri['capacity']
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

}
