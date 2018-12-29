<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('KhachhangInterface');

class Mod_khachhang extends MY_Model implements KhachhangInterface {

    var $table = 'congty';
    var $congty_table = "congty";
    var $contact_table = "contact";
    var $congty_contact_table = "congty_contact";
    var $column = array('congty_id', 'congty_name', 'congty_address');
    var $order = array('congty_id' => 'DESC');
    var $status = array('congty_status' => '1');

    //Datatable

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
    
    function check_phone_congty($congty_phone, $id_congty) {
        if ($id_congty == '0' || $id_congty == '' || !isset($id_congty)) {
            $dieukien = array(
                'congty_status' => '1',
                'congty_phone' => trim($congty_phone)
            );
        } else {
            $dieukien = "congty_status='1' and congty_phone='" . trim($congty_phone) . "' and congty_id!='" . $id_congty . "'";
        }
        $dulieu = $this->db->select('*')->from($this->congty_table)->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_email_congty($congty_email, $id_congty) {
        if ($id_congty == '0' || $id_congty == '' || !isset($id_congty)) {
            $dieukien = array(
                'congty_status' => '1',
                'congty_email' => trim($congty_email)
            );
        } else {
            $dieukien = "congty_status='1' and congty_email='" . trim($congty_email) . "' and congty_id!='" . $id_congty . "'";
        }
        $dulieu = $this->db->select('*')->from($this->congty_table)->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_tax_congty($congty_tax, $id_congty) {
        if ($congty_id == '0' || $id_congty == '' || !isset($id_congty)) {
            $dieukien = array(
                'congty_status' => '1',
                'congty_tax' => trim($congty_tax)
            );
        } else {
            $dieukien = "congty_status='1' and congty_tax='" . trim($congty_tax) . "' and congty_id!='" . $id_congty . "'";
        }
        $dulieu = $this->db->select('*')->from($this->congty_table)->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
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

    //End datatable
    function congty_add($data) {
        $insert = array(
            'congty_name' => $data['name'],
            'congty_address' => $data['address'],
            'congty_phone' => $data['phone'],
            'congty_fax' => $data['fax'],
            'congty_email' => $data['email'],
            'congty_tax' => $data['tax'],
            'congty_status' => '1',
        );
        $this->db->insert($this->congty_table, $insert);
        $congty_id = $this->db->insert_id();
        $dulieu = $this->db->select('*')->from($this->congty_table)->where('congty_id', $congty_id)->get();
        return $dulieu->result();
    }
    
    function check_update_register($data) {
        $update = array(
            'congty_name' => $data['name'],
            'congty_address' => $data['address'],
            'congty_phone' => $data['phone'],
            'congty_fax' => $data['fax'],
            'congty_email' => $data['email'],
            'congty_tax' => $data['tax'],
            'congty_status' => '1',
        );
        $this->db->where('congty_id', $data['id']);
        return $this->db->update($this->congty_table, $update);
    }

    function xoacongty($id) {
        $mangxuly = array(
            'congty_status' => '2'
        );
        $this->db->where('congty_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function suacongty($data, $giongcu) {
//        $kiemtra = $this->db->select('congty_id')->from($this->table)->where("congty_name='" . $data['congty_name'] . "' and congty_id!='" . $data['congty_id'] . "'")->get(); // and congty_status='0'
//        if($data['congty_name']==$giongcu){$kiemtra2=0;}else{$kiemtra2=1;};
//        if ($kiemtra->num_rows() == 0||$kiemtra2==0) {
        $this->db->where('congty_id', $data['congty_id']);
        return $this->db->update($this->table, $data);
//        } else {
//            return false;
//        }
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('congty_status','0')
    }
   
    function chitietcongty($congty_id){
        return $this->db->select('*')->from($this->table)->where('congty_id',$congty_id)->get()->result();
    }
    
    function get_info($congty_id) {
        $dulieu = $this->db->select("*")->from($this->congty_table)->where('congty_id', $congty_id)->get();
        return $dulieu->result_array();
    }

}

/* End of file */