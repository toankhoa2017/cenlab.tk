<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_chat extends MY_Model implements NenmauInterface {

    var $table_file = "mau_chitieu_chat as a, mau_chat as b";
    var $table_select = "*";
    var $column = array('chat_name', 'chat_describe');
    var $chat_table = "mau_chat";
    var $chitieu_chat_table = "mau_chitieu_chat";

    private function get_datatables_query($id_chitieu) {
        $dieukien = "a.chitieu_id='" . $id_chitieu . "' and a.chat_id=b.chat_id and b.chat_status='1'";
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien)->order_by("b.chat_id", "desc");
        $i = 0;
        foreach ($this->column as $item) {
            $tukhoa = trim(@$_POST['search']['value']);
            if ($tukhoa) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $tukhoa);
                } else {
                    $this->db->or_like($item, $tukhoa);
                }
                if (count($this->column) - 1 == $i)
                    $this->db->group_end();
            }
            $column[$i] = $item;
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function danhsach_chat($id_chitieu) {
        $dieukien = "a.chitieu_id='" . $id_chitieu . "' and a.chat_id=b.chat_id and b.chat_status='1'";
        $this->get_datatables_query($id_chitieu);
        if (@$_POST['length'] != -1)
            $this->db->where($dieukien)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id_chitieu) {
        $dieukien = "a.chitieu_id='" . $id_chitieu . "' and a.chat_id=b.chat_id and b.chat_status='1'";
        $this->get_datatables_query($id_chitieu);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($id_chitieu) {
        $dieukien = "a.chitieu_id='" . $id_chitieu . "' and a.chat_id=b.chat_id and b.chat_status='1'";
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien);
        return $this->db->count_all_results();
    }

    function them_chat($data) {
        $this->db->insert($this->chat_table, $data);
        return $this->db->insert_id();
    }

    function them_chitieu_chat($data) {
        return $this->db->insert($this->chitieu_chat_table, $data);
    }

    function get_congnhan($id_chat) {
        $dulieu = $this->db->select('b.*')->from('mau_congnhan_chat as a,mau_congnhan as b')->where("a.chat_id='" . $id_chat . "' and a.congnhan_id=b.congnhan_id and b.congnhan_status='1'")->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function them_congnhan($data) {
        return $this->db->insert("mau_congnhan_chat", $data);
    }

    function xoa_chat($chat_id,$chitieu_id,$nenmau_id) {
        $update = array(
            'chat_status' => '2'
        );
        $this->db->where("chat_id", $chat_id);
        $this->db->update("mau_chat", $update);
        $data = array(
            'chat_id' => $chat_id,
            'chitieu_id' => $chitieu_id,
        );
        $this->db->where($data);
        $this->db->delete("mau_chitieu_chat");
    }

    function sua_chat($data) {
        $this->db->where('chat_id', $data['chat_id']);
        $this->db->update('mau_chat', $data);
    }

    function sua_xoa_quanhe_congnhan($chat_id) {
        $this->db->where('chat_id', $chat_id);
        $this->db->delete('mau_congnhan_chat');
    }

    function sua_quanhe_congnhan($chat_id, $congnhan_moi) {
        $update = array(
            'chat_id' => $chat_id,
            'congnhan_id' => $congnhan_moi
        );
        $this->db->insert('mau_congnhan_chat', $update);
    }

    function mau_chitieu_chat($data) {
        $dieukien = array(
            'chat_id' => $data['chat_id'],
            'chitieu_id' => $data['chitieu_id'],
        );
        $this->db->where($dieukien);
        $this->db->update('mau_chitieu_chat', $data);
    }

}