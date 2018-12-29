<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('KhachhangInterface');

class Mod_contact extends MY_Model implements KhachhangInterface {

    var $table = "contact";
    var $congty_table = "congty";
    var $contact_table = "contact";
    var $congty_contact_table = "congty_contact";
    var $table_file = "congty_contact as a, contact as b";
    var $column = array('b.contact_lastname', 'b.contact_firstname', 'b.contact_email' , 'b.contact_phone');
    var $order = array('b.contact_id' => 'DESC');

    private function get_datatables_query($congty_id){
        $dieukien = "a.congty_id='" . $congty_id . "' and a.contact_id=b.contact_id and b.contact_status=1";
        $this->db->from($this->table_file)->where($dieukien);
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

    function list_contact($congty_id) {
        $this->get_datatables_query($congty_id);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_all($congty_id) {
        $dieukien = "a.congty_id='" . $congty_id . "' and a.contact_id=b.contact_id and b.contact_status=1";
        $this->db->from($this->table_file)->where($dieukien);
        return $this->db->count_all_results();
    }

    function count_filtered($congty_id) {
        $this->get_datatables_query($congty_id);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function check_phone_contact($contact_phone, $contact_id) {
        if (!isset($contact_id) || $contact_id == '' || $contact_id == '0') {
            $dieukien = array(
                'contact_status' => '1',
                'contact_phone' => trim($contact_phone)
            );
        } else {
            $dieukien = "contact_status='1' and contact_phone='" . trim($contact_phone) . "' and contact_id!='" . $contact_id . "'";
        }
        $dulieu = $this->db->select('*')->from($this->contact_table)->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }

    function check_email_contact($contact_email, $contact_id) {
        if (!isset($contact_id) || $contact_id == '' || $contact_id == '0') {
            $dieukien = array(
                'contact_status' => '1',
                'contact_email' => trim($contact_email)
            );
        } else {
            $dieukien = "contact_status='1' and contact_email='" . trim($contact_email) . "' and contact_id!='" . $contact_id . "'";
        }
        $dulieu = $this->db->select('*')->from($this->contact_table)->where($dieukien)->get();
        if ($dulieu->num_rows() == 0) {
            return true;
        } else {
            return false;
        }
    }
    
    function rand_matkhau($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    function contact_add($data) {
        $ngaysinh = $data['ngaysinh'];
        $ngay = explode("-", $ngaysinh);
        $ngayformat = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayformat = $ngayformat . trim($ngay[$i]);
            } else {
                $ngayformat = $ngayformat . trim($ngay[$i]) . "-";
            }
        }
        $insert = array(
            'contact_lastname' => $data['ho'],
            'contact_firstname' => $data['ten'],
            'contact_fullname' => $data['ho'] . " " . $data['ten'],
            'contact_email' => $data['email'],
            'contact_phone' => $data['phone'],
            'contact_birthday' => $ngayformat,
            'contact_password' => $this->rand_matkhau(6),
        );
        $this->db->insert($this->contact_table, $insert);
        $contact_id = $this->db->insert_id();
        $dulieu = array(
            'contact_id' => $contact_id,
            'congty_id' => $data['congty_id']
        );
        $this->db->insert($this->congty_contact_table, $dulieu);
        $dulieu = $this->db->select('*')->from($this->contact_table)->where('contact_id', $contact_id)->get();
        return $dulieu->result();
    }

    function xoanguoilienhe($id) {
        $mangxuly = array(
            'contact_status' => '2'
        );
        $this->db->where('contact_id', $id);
        return $this->db->update($this->table, $mangxuly);
    }

    function contact_update($data_update) {
        $dieukien = array(
            'contact_id' => $data_update['contact_id']
        );
        $this->db->where($dieukien);
        $update = array(
            'contact_lastname' => $data_update['contact_lastname'],
            'contact_firstname' => $data_update['contact_firstname'],
            'contact_email' => $data_update['contact_email'],
            'contact_phone' => $data_update['contact_phone'],
            'contact_birthday' => $data_update['contact_birthday'],
        );
        return $this->db->update($this->contact_table, $update);
    }

    function danhsach() {
        return $this->db->select('*')->from($this->table)->get()->result(); //->where('congty_status','0')
    }
   

}

/* End of file */