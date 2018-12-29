<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('KhachhangInterface');

class Mod_api extends MY_Model implements KhachhangInterface {

    var $congty_table = "congty";
    var $contact_table = "contact";
    var $congty_contact_table = "congty_contact";
    var $table_file = "";
    var $column = array('b.contact_lastname', 'b.contact_firstname', 'b.contact_fullname');
    var $order = array('b.contact_id' => 'DESC');

    function list_contact($key_congty, $congty_id) {
        if (isset($congty_id)) {
            $dieukien = "a.contact_id = b.contact_id and b.congty_id='" . $congty_id . "'";
            $dulieu = $this->db->select("*")->from("contact as a , congty_contact as b")->where($dieukien)->like('a.contact_fullname', $key_congty)->order_by("a.contact_fullname", "asc")->get();
            $mang1 = $dulieu->result();
            $loai = array();
            foreach ($mang1 as $row) {
                $loai[] = $row->contact_id;
            }
            $loai1 = implode(",", $loai);
            if (count($loai) == 0) {
                $loai3 = "contact_status = 1";
            } else {
                $loai3 = "contact_status = 1 and contact_id NOT IN (" . $loai1 . ")";
            }
            $dulieu2 = $this->db->select("*,DATE_FORMAT(contact_birthday,'%d-%m-%Y') AS contact_birthday")->from("contact")->where($loai3)->like('contact_fullname', $key_congty)->order_by("contact_fullname", "asc")->get();
            $mang2 = $dulieu2->result();
            $mang3 = $mang1 + $mang2;
            return $mang3;
        } else {
            $dieukien = array(
                'contact_status' => '1'
            );
            $dulieu = $this->db->select("*,DATE_FORMAT(contact_birthday,'%d-%m-%Y') AS contact_birthday")->from($this->contact_table)->where($dieukien)->like('contact_fullname', $key_congty)->order_by("contact_fullname", "asc")->get();
            return $dulieu->result();
        }
    }

    function list_congty($key_congty) {
        $dulieu = $this->db->select("*")->from($this->congty_table)->where('congty_status', '1')->like('congty_name', $key_congty)->order_by("congty_name", "asc")->get();
        return $dulieu->result();
    }
    
    function list_all_congty() {
        $dulieu = $this->db->select("congty_id, congty_name")->from($this->congty_table)->where('congty_status', '1')->order_by("congty_name", "asc")->get();
        return $dulieu->result();
    }
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
        return $dulieu->row();
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

    function get_info($congty_id) {
        $dulieu = $this->db->select("*")->from($this->congty_table)->where('congty_id', $congty_id)->get();
        return $dulieu->result_array();
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
        return $dulieu->row();
    }

    function contact_remove($data) {
        $this->db->where('contact_id', $data['contact_id']);
        $dulieu = array(
            'contact_status' => '2',
        );
        return $this->db->update($this->contact_table, $dulieu);
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

    function add_congty_contact($congty_id, $contact_id) {
        $congty = $this->db->select("*")->from($this->congty_table)->where("congty_id", $congty_id)->get();
        $contact = $this->db->select("*")->from($this->contact_table)->where("contact_id", $contact_id)->get();
        $check_congty = $congty->num_rows();
        $check_contact = $contact->num_rows();
        if ($check_congty > 0 && $check_contact > 0) {
            $insert = array(
                'congty_id' => $congty_id,
                'contact_id' => $contact_id
            );
            $kiemtra = $this->db->select("*")->from($this->congty_contact_table)->where($insert)->get()->num_rows();
            if ($kiemtra == 0) {
                return $this->db->insert($this->congty_contact_table, $insert);
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    function get_congty($congty_id){
        $dieukien = array(
            'congty_id' => $congty_id,
            'congty_status' => '1',
        );
        $dulieu = $this->db->select("*")->from($this->congty_table)->where($dieukien)->get();
        if($dulieu->num_rows()>0){
            return $dulieu->result();
        }else{
            return false;
        }
    }

    function get_contact($contact_id){
        $dieukien = array(
            'contact_id' => $contact_id,
            'contact_status' => '1',
        );
        $dulieu = $this->db->select("*")->from($this->contact_table)->where($dieukien)->get();
        if($dulieu->num_rows()>0){
            return $dulieu->result();
        }else{
            return false;
        }
    }
    
}
