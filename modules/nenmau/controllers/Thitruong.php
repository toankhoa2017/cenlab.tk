<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thitruong extends ADMIN_Controller {

    private $privcheck;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('thitruong');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_PTN_THITRUONG];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_thitruong');
    }

    function index() {
        if (!$this->privcheck['read'])
            redirect(site_url() . 'admin/denied?w=read');
        $this->parser->parse('thitruong/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_thitruong->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->thitruong_name;
            $button = '';
            $button .= ($this->privcheck['write']) ? ' <button class="btn btn-xs btn-purple" data-toggle="tooltip" title="'.$ngonngu['tooltip_themchat'].'" onclick="re_direct(' . $item->thitruong_id . ')"><i class="ace-icon fa fa-plus"></i></button>' : '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->thitruong_id . ',\'' . $item->thitruong_name . '\',\'' . $item->phuongphap_describe . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->thitruong_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_thitruong->count_all(),
            "recordsFiltered" => $this->mod_thitruong->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_thitruong->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoathitruong() {
        $id_thitruong = $this->input->post("idthitruong");
        $id_chat = $this->input->post("idchat");
        $data = array(
            'chat_id' => $id_chat,
            'thitruong_id' => $id_thitruong,
        );
        $this->mod_thitruong->xoathitruong($data);
        echo "1";
    }

    function suathitruong() {
        $id_thitruong = $this->input->post("idthitruongsua");
        $id_chat_cu = $this->input->post("idchatcu");
        $id_chat_moi = $this->input->post("idchatmoi");
        $start = $this->input->post("start");
        $end = $this->input->post("end");
        $data = array(
            'chat_id' => $id_chat_moi,
            'thitruong_id' => $id_thitruong,
            'mrl_min' => $start == "" ? NULL : $start,
            'mrl_max' => $end == "" ? NULL : $end
        );
        $dieukien = array(
            'chat_id' => $id_chat_cu,
            'thitruong_id' => $id_thitruong,
        );
        $kiemtra = $this->mod_thitruong->suathitruong($data, $dieukien);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function chitietthitruong($id_thitruong) {
        if (!$this->privcheck['read'])
            redirect(site_url() . 'admin/denied?w=read');
        $data = $this->mod_thitruong->thongtin_thitruong($id_thitruong);
        $this->parser->assign('id_thitruong', $data);
        $data_chat = $this->mod_thitruong->get_chat();
        $chat = array();
        foreach ($data_chat as $row) {
            $chat[$row->chat_id] = $row->chat_name;
        }
        $this->parser->assign('chat', $chat);
        $this->parser->parse('thitruong/list_chat');
    }

    function ajax_list_chat() {
        $ngonngu = $this->lang->getLang();
        $thitruong_id = $this->input->post('id_thitruong');
        $list = $this->mod_thitruong->get_datatables_chat($thitruong_id);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->chat_name;
            $row[] = $item->mrl_min;
            $row[] = $item->mrl_max;
            $button = '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->chat_id . ',\'' . $item->mrl_min . '\',\'' . $item->mrl_max . '\')" data-toggle="tooltip" title="'.$ngonngu['themchat_tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->thitruong_id . ',' . $item->chat_id . ')" data-toggle="tooltip" title="'.$ngonngu['themchat_tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_thitruong->count_all_chat($thitruong_id),
            "recordsFiltered" => $this->mod_thitruong->count_filtered_chat($thitruong_id),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function ajax_add_chat() {
        $items = $this->input->post();
        $kiemtra = $this->mod_thitruong->_create_chat($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function goiy_chat() {
        $tukhoa = $this->input->post('key');
        $dulieu = $this->mod_thitruong->goiy_chat($tukhoa);
        if ($dulieu == true && $tukhoa != "") {
            ?>
            <div class="autosuggest scrollbarr" style="width: 100%">
                <ul role="listbox" tabindex="0" style="margin-top:3px;margin-bottom: 3px;width:100%">
                        <?php
                        foreach ($dulieu as $row) {
                            ?>
                        <li style="padding:5px;margin:2px" tabindex="-1" onClick="chat_check('<?= $row->chat_id ?>')"><?php echo $row->chat_name ?></li>
                <?php
            }
            ?>
                </ul>
            </div>
            <?php
        }
    }

    function check_chat() {
        $chat_id = $this->input->post('id_chat');
        $thitruong_id = $this->input->post('id_thitruong');
        $data = array(
            'chat_id' => $chat_id,
            'thitruong_id' => $thitruong_id,
        );
        $dulieu = $this->mod_thitruong->check_chat($data);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function xoa_thitruong() {
        $id = $this->input->post('idthitruongxoa');
        $dulieu = $this->mod_thitruong->xoa_thitruong($id);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function sua_thitruong() {
        $id_thitruong = $this->input->post("idthitruongsua");
        $name_thitruong = $this->input->post("namethitruongsua");
        $dulieu = $this->mod_thitruong->sua_thitruong($id_thitruong, $name_thitruong);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function load_ds_chat() {
        $thitruong_id = $this->input->post('id_thitruong');
        $id_chat = $this->input->post('id_sua');
        $dulieu = $this->mod_thitruong->load_ds_chat($thitruong_id, $id_chat);
        ?>
            <option value="">-- Select --</option>
        <?php
        foreach ($dulieu as $row) {
            ?>
            <option dvtinh="<?= $row->donvitinh_name ?>" value="<?= $row->chat_id ?>"><?= $row->nenmau_name . ' - ' . $row->chat_name ?></option>
            <?php
        }
    }

}
