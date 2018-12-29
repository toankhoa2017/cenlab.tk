<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_chat extends MY_Model implements NenmauInterface {
    var $table = 'mau_chitieu_chat';
    var $column = array('dongia_id', 'chat_id'); //set column field database for order and search
    var $order = array('dongia_id' => 'DESC'); // default order 
    var $chat_table = "mau_chat";
    var $chat_gia = "mau_chatgia";
    private function _get_datatables_query($dongia) {
        $this->db->select('
            cc.dongia_id as chitieu_id, cc.chat_id, cc.capacity, cc.val_min, cc.val_max, cc.dung_sai,
            chat.chat_name,
            chat.chat_name_eng,
            chat.chat_describe
        ');
        $this->db->from($this->table.' cc');
        $this->db->join('mau_chat chat', 'cc.chat_id = chat.chat_id');
        $dieukien = array(
            'cc.dongia_id' => $dongia
        );
        $this->db->where($dieukien);
        $i = 0;
        foreach ($this->column as $item) {//loop column 
            if (@$_POST['search']['value']) {//if datatable send POST for search
                if ($i === 0) {//first loop
                    $this->db->group_start(); //open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                //last loop
                if (count($this->column) - 1 == $i)
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; //set column array variable to order processing
            $i++;
        }
        if (isset($_POST['order'])) {//here order processing
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables($dongia) {
        $this->_get_datatables_query($dongia);
        if (@$_POST['length'] != -1) $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get(); //->where($this->status, "0")
        //echo $this->db->last_query();
        return $query->result();
    }
    function them_chat($data) {
        $this->db->insert($this->chat_table, $data);
        return $this->db->insert_id();
    }

    function them_chitieu_chat($data) {
        return $this->db->insert($this->table, $data);
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
            'dongia_id' => $chitieu_id,
        );
        $this->db->where($data);
        $this->db->delete("mau_chitieu_chat");
    }

    function sua_chat($data) {
        $this->db->where('chat_id', $data['chat_id']);
        $this->db->update('mau_chat', $data);
    }
    function mau_chitieu_chat($data) {
        $dieukien = array(
            'chat_id' => $data['chat_id'],
            'dongia_id' => $data['dongia_id'],
        );
        $this->db->where($dieukien);
        $this->db->update('mau_chitieu_chat', $data);
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
    function count_filtered($dongia) {
        $this->_get_datatables_query($dongia);
        $query = $this->db->get(); //->where($this->status, "0")
        return $query->num_rows();
    }
    function count_all($dongia) {
        $this->db->from($this->table); //->where($this->status, "0")
        $dieukien = array(
            'dongia_id' => $dongia
            //'donvi_status' => '0'
        );
        $this->db->where($dieukien);
        return $this->db->count_all_results();
    } 
    function setgia($data){
        $dongia_id = $data[0]["bo_id"];
        $this->db->where('bo_id', $dongia_id);
        $this->db->delete($this->chat_gia);
        return $this->db->insert_batch($this->chat_gia, $data);
    }
    function getGiaByDongia($dongia_id){
        $query = $this->db->select("*")->from($this->chat_gia)->where(array("bo_id" => $dongia_id, "khachhang_id" => NULL))->order_by('gia_order', 'ASC')->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function getGiaByDongiaKhachhang($dongia_id, $khachhang_id){
        $query = $this->db->select("*")->from($this->chat_gia)->where(array("bo_id" => $dongia_id, "khachhang_id" => $khachhang_id))->order_by('gia_order', 'ASC')->get();
        $result = $query->result_array();
        $query->free_result();
        return $result;
    }
    function goiy_chat($key) {
        $dulieu = $this->db->select("*")->from("mau_chat")->where('chat_status', '1')->like('chat_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }
    function getDonGiaInfo($dongia_id){
        $query = $this->db->select("*")->from("mau_dongia")->where(array("dongia_id" => $dongia_id))->get();
        $result = $query->row();
        $query->free_result();
        return $result;
    }
}