<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_congnhan extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_congnhan';
    var $column = array('congnhan_id', 'congnhan_name', 'congnhan_describe');
    var $order = array('congnhan_id' => 'DESC');
    var $status = array('congnhan_status' => '1');
    var $table1 = 'mau_congnhan as a';

    private function get_datatables_query() {
        $dieukien = "a.congnhan_status=1";
        $this->db->from($this->table1)->where($dieukien);
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
        $dieukien = "a.congnhan_status=1";
        $this->get_datatables_query();
        if (@$_POST['length'] != -1)
            $this->db->where($dieukien)->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $dieukien = "a.congnhan_status=1";
        $this->get_datatables_query();
        $query = $this->db->where($dieukien)->get();
        return $query->num_rows();
    }

    function count_all() {
        $dieukien = "a.congnhan_status=1";
        $this->db->from($this->table1);
        return $this->db->where($dieukien)->count_all_results();
    }

    function _create($values) {
        $ngayketthuc = $values['ngayhethan'];
        $ngay = explode("-", $ngayketthuc);
        $ngayformat = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayformat = $ngayformat . $ngay[$i];
            } else {
                $ngayformat = $ngayformat . $ngay[$i] . "-";
            }
        }
        $insert = array(
            'congnhan_name' => $values['name'],
            'congnhan_sign' => $values['kihieu'],
            'congnhan_logo' => $values['file_id'],
            'congnhan_dateend' => $ngayformat,
            'congnhan_status' => '1'
        );
        return $this->db->insert($this->table, $insert);
    }

    function xoacongnhan($id) {
        $mangxuly = array(
            'congnhan_status' => '2'
        );
        $this->db->where('congnhan_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function suacongnhan($data) {
        $this->db->where('congnhan_id', $data['congnhan_id']);
        return $this->db->update($this->table, $data);
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('congnhan_status','0')
    }
    
    function trungsign($items){
        if($items["id_sua"]){
            $dieukien = array(
                'congnhan_sign' => $items['kihieu'], 
                'congnhan_status' => '1',
                'congnhan_id !=' => $items["id_sua"]
            );
        }else{
            $dieukien = array(
                'congnhan_sign' => $items['kihieu'], 
                'congnhan_status' => '1'
            );
        }
        return $this->db->select('*')->from($this->table)->where($dieukien)->get()->result();
    }
}