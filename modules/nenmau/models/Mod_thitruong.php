<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_thitruong extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_thitruong';
    var $column = array('thitruong_id', 'thitruong_name');
    var $order = array('thitruong_id' => 'DESC');
    var $status = array('thitruong_status' => '1');
    var $table_chat = 'mau_thitruong_chat as a, mau_chat as b';

    private function get_datatables_query_chat($thitruong_id) {
        $dieukien_chat = 'a.chat_id=b.chat_id and a.thitruong_id=' . $thitruong_id;
        $this->db->from($this->table_chat);
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

    function get_datatables_chat($thitruong_id) {
        $dieukien_chat = 'a.chat_id=b.chat_id and a.thitruong_id=' . $thitruong_id;
        $this->get_datatables_query_chat($thitruong_id);
        if (@$_POST['length'] != -1)
            $this->db->where($dieukien_chat)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_chat($thitruong_id) {
        $dieukien_chat = 'a.chat_id=b.chat_id and a.thitruong_id=' . $thitruong_id;
        $this->get_datatables_query_chat($thitruong_id);
        $query = $this->db->where($dieukien_chat)->get();
        return $query->num_rows();
    }

    function count_all_chat($thitruong_id) {
        $dieukien_chat = 'a.chat_id=b.chat_id and a.thitruong_id=' . $thitruong_id;
        $this->db->from($this->table_chat);
        return $this->db->where($dieukien_chat)->count_all_results();
    }

    function _create($values) {
        $dieukien = array(
            'thitruong_status' => '1',
            'thitruong_name' => $values['name']
        );
        $kiemtra = $this->db->select('*')->from('mau_thitruong')->where($dieukien)->get();
        if ($kiemtra->num_rows() > 0) {
            return false;
        } else {
            $insert = array(
                'thitruong_name' => $values['name'],
                'thitruong_status' => '1'
            );
            return $this->db->insert($this->table, $insert);
        }
    }

    function xoathitruong($dieukien) {
        $this->db->where($dieukien);
        return $this->db->delete('mau_thitruong_chat');
    }

    function xoa_thitruong($id) {
        $this->db->where("thitruong_id", $id);
        $update = array(
            'thitruong_status' => '2'
        );
        return $this->db->update('mau_thitruong', $update);
    }

    function suathitruong($data, $dieukien) {
        $this->db->where($dieukien);
        return $this->db->update('mau_thitruong_chat', $data);
    }

    function sua_thitruong($id_thitruong, $name_thitruong) {
        $kiemtra = $this->db->select('thitruong_id')->from($this->table)->where("thitruong_name='" . $name_thitruong . "' and thitruong_id!='" . $id_thitruong . "' and thitruong_status='1'")->get();
        if ($kiemtra->num_rows() > 0) {
            return false;
        } else {
            $update = array(
                'thitruong_name' => $name_thitruong,
            );
            $this->db->where('thitruong_id', $id_thitruong);
            return $this->db->update($this->table, $update);
        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('thitruong_status','0')
    }

    function thongtin_thitruong($id_thitruong) {
        return $this->db->select('*')->from($this->table)->where('thitruong_id', $id_thitruong)->get()->result();
    }

    private function get_datatables_query() {
        $this->db->from($this->table);
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

    function get_datatables() {
        $this->get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->where($this->status)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->get_datatables_query();
        $query = $this->db->where($this->status)->get();
        return $query->num_rows();
    }

    function count_all() {
        $this->db->from($this->table);
        return $this->db->where($this->status)->count_all_results();
    }

    function _create_chat($values) {
        $insert = array(
            'chat_id' => $values['name_id'],
            'thitruong_id' => $values['thitruong'],
            'mrl_min' => $values['start'] == "" ? NULL : $values['start'],
            'mrl_max' => $values['end'] == "" ? NULL : $values['end']
        );
        return $this->db->insert('mau_thitruong_chat', $insert);
    }

    function goiy_chat($key) {
        $dulieu = $this->db->select("*")->from('mau_chat')->where('chat_status', '1')->like('chat_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function get_chat() {
        $dulieu = $this->db->select('*')->from('mau_chat')->where('chat_status', '1')->get();
        return $dulieu->result();
    }

    function check_chat($data) {
        $dulieu = $this->db->select('*')->from('mau_thitruong_chat')->where($data)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function load_ds_chat($thitruong_id, $id_chat) {
        $dulieu = $this->db->select("chat_id")->from('mau_thitruong_chat')->where('thitruong_id', $thitruong_id)->get();
        $dulieu1 = $dulieu->result();
        $loai = array();
        $stt = 0;
        foreach ($dulieu1 as $row) {
            $stt++;
            if ($id_chat != $row->chat_id) {
                $loai[] = $row->chat_id;
            }
        }
        $loai = implode(",", $loai);
        if ($loai == "") {
            $dieukien = "chat_status ='1'";
            $this->db->select('chat.chat_id,chat.chat_name,nenmau.nenmau_name, dvtinh.donvitinh_name');
            $this->db->from('mau_chat as chat');
            $this->db->join('mau_chitieu_chat as chitieu_chat', 'chat.chat_id = chitieu_chat.chat_id');
            $this->db->join('mau_dongia as dongia', 'dongia.dongia_id = chitieu_chat.dongia_id');
            $this->db->join('mau_nenmau as nenmau', 'nenmau.nenmau_id = dongia.nenmau_id');
            $this->db->join('mau_donvitinh as dvtinh', 'dvtinh.donvitinh_id = dongia.donvitinh_id');
            $this->db->where($dieukien);
        } else {
            $dieukien = "a.chat_id NOT IN(" . $loai . ") and b.thitruong_id='" . $thitruong_id . "' and chat_status ='1'";
            $this->db->distinct('a.chat_id');
            $this->db->select('a.chat_id,a.chat_name, nenmau.nenmau_name, dvtinh.donvitinh_name');
            $this->db->from('mau_chat as a,mau_thitruong_chat as b');
            $this->db->join('mau_chitieu_chat as chitieu_chat', 'a.chat_id = chitieu_chat.chat_id');
            $this->db->join('mau_dongia as dongia', 'dongia.dongia_id = chitieu_chat.dongia_id');
            $this->db->join('mau_nenmau as nenmau', 'nenmau.nenmau_id = dongia.nenmau_id');
            $this->db->join('mau_donvitinh as dvtinh', 'dvtinh.donvitinh_id = dongia.donvitinh_id');
            $this->db->where($dieukien);
        }
        $dulieu = $this->db->get();
        return $dulieu->result();
    }

}