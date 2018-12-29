<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_nenmau extends MY_Model implements NenmauInterface {

    var $parent = 0;
    var $table = 'mau_nenmau';
    var $mau_dieukienluu = 'mau_dieukienluu';
    var $listUps = array();
    var $listCheck = array();
    
    function _create($values) {
        if ($values['dieukienluu_id'] == 0) {
            $insert = array(
                'dieukienluu_name' => $values['dieukienluu'],
                'dieukienluu_status' => '1'
            );
            $this->db->insert($this->mau_dieukienluu, $insert);
            $dieukienluu_id = $this->db->insert_id();
        } else {
            $dieukienluu_id = $values['dieukienluu_id'];
        }
        $kiemtra = array(
            'nenmau_idparent' => $values['idparent'],
            'nenmau_name' => $values['name'],
            'nenmau_name_eng' => $values['name_eng'],
        );
        $insert = array(
            'nenmau_idparent' => $values['idparent'],
            'nenmau_name' => $values['name'],
            'nenmau_mota' => $values['mota'],
            'nenmau_name_eng' => $values['name_eng'],
            'donvi_id' => $values['donvi']
        );
        $kiemtra = $this->db->select("*")->from($this->table)->where($kiemtra)->get();
        if ($kiemtra->num_rows() > 0) {
            return FALSE;
        } else {
            if ($values['ref'])
                $insert['nenmau_ref'] = $values['ref'];
            if (!$this->db->insert($this->table, $insert)) {
                return FALSE;
            } else {
                $nenmau_id = $this->db->insert_id();
                $nenmau_dieukienluu = array(
                    'nenmau_id' => $nenmau_id,
                    'dieukienluu_id' => $dieukienluu_id
                );
                $this->db->insert("mau_nenmau_dieukienluu", $nenmau_dieukienluu);
                return TRUE;
            }
        }
    }

    function _levelUps($id, $root = 0) {
        if (trim($id) != '') {
            $this->listCheck[] = $id; //Dua vao listCheck de kiem tra dieu kien dung
            $this->db->select('nenmau_idparent, nenmau_name');
            $this->db->from($this->table);
            $this->db->where('nenmau_id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            $nenmau = array(
                'id' => $id,
                'name' => $result['nenmau_name']
            );
            $query->free_result();
            array_unshift($this->listUps, $nenmau);
            if (($result['nenmau_idparent'] != 0) && ($id != $root) && (!in_array($result['nenmau_idparent'], $this->listCheck))) {
                $this->_levelUps($result['nenmau_idparent'], $root);
            }
        }
        return FALSE;
    }

    function _getRef($id) {
        $this->db->select('nenmau_ref ref');
        $this->db->from($this->table);
        $this->db->where('nenmau_id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }

    function xoanenmau($id) {
        $update = array(
            'nenmau_status' => '2'
        );
        $this->db->where('nenmau_id', $id);
        return $this->db->update($this->table, $update);
    }

    function suanenmau($data, $giongcu) {
        $kiemtra = $this->db->select('nenmau_id')->from($this->table)->where("nenmau_name='" . $data['nenmau_name'] . "' and nenmau_name_eng='" . $data['nenmau_name_eng'] . "' and nenmau_id!='" . $data['nenmau_id'] . "'")->get(); // and nenmau_status='0'
        if ($data['nenmau_name'] == $giongcu) {
            $kiemtra2 = 0;
        } else {
            $kiemtra2 = 1;
        };
        if ($kiemtra->num_rows() == 0 || $kiemtra2 == 0) {
            $this->db->where('nenmau_id', $data['nenmau_id']);
            return $this->db->update($this->table, $data);
        } else {
            return false;
        }
    }

    function review($data) {
        $dulieu = $this->db->select('*')->from($this->table)->where($data)->get();
        return $dulieu->result();
    }

    function danhsach($key) {
        if ($key == "") {
            $dieukien = "a.nenmau_id=c.nenmau_id and b.dieukienluu_id=c.dieukienluu_id and a.nenmau_status='1'";
            return $this->db->select('*')->from("mau_nenmau as a, mau_dieukienluu as b , mau_nenmau_dieukienluu as c")->where($dieukien)->order_by("nenmau_idparent", "desc")->get()->result();
        } else {
            $dieukien = "a.nenmau_id=c.nenmau_id and b.dieukienluu_id=c.dieukienluu_id and a.nenmau_status='1'";
            return $this->db->select('*')->from("mau_nenmau as a, mau_dieukienluu as b , mau_nenmau_dieukienluu as c")->where($dieukien)->like('nenmau_name', $key)->order_by("nenmau_idparent", "asc")->get()->result();
        }
    }

    function review1($id) {
        $dieukien = array(
            'nenmau_status' => '1',
            'nenmau_id' => $id
        );
        $dulieu = $this->db->select("*")->from($this->table)->where($dieukien)->get();
        $dulieu = $dulieu->result();
        return $dulieu[0]->nenmau_name;
    }

    function goiy_dieukienluu($key) {
        $dulieu = $this->db->select("*")->from($this->mau_dieukienluu)->where('dieukienluu_status', '1')->like('dieukienluu_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function capnhat_dieukienluu($id, $name) {
        if ($id == 0) {
            $dieukienluu = array(
                'dieukienluu_name' => $name,
                'dieukienluu_status' => '1'
            );
            $kiemtra = $this->select("*")->from("mau_dieukienluu")->where($dieukienluu)->get();
            if ($kiemtra->num_rows() == 0) {
                $this->db->insert("mau_dieukienluu", $dieukienluu);
                return $this->db->insert_id();
            } else {
                $dulieu = $kiemtra->result();
                return $dulieu[0]->dieukienluu_id;
            }
        } else {
            return $id;
        }
    }

    function update_nenmau_dieukienluu($moi, $nenmau_id) {
        $this->db->where('nenmau_id', $nenmau_id);
        $this->db->delete('mau_nenmau_dieukienluu');
        $this->db->insert('mau_nenmau_dieukienluu', $moi);
        return $this->db->insert_id();
    }

    function kiemtracon($id_nenmau) {
        $dulieu = $this->db->select('nenmau_id')->from($this->table)->where('nenmau_idparent', $id_nenmau)->order_by('nenmau_idparent', 'asc')->get();
        if ($dulieu->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function load_select_nenmau() {
        $dieukien = array(
            "nenmau_status" => "1",
        );
        $dulieu = $this->db->select("*")->from("mau_nenmau")->where($dieukien)->get();
        return $dulieu->result();
    }

    function info_nenmau($id) {
        $dulieu = $this->db->select("*")->from($this->table)->where("nenmau_id", $id)->get();
        return $dulieu->result();
    }

    var $column1 = array();
    var $order1 = array('giabo_id' => 'DESC');

    private function get_datatables_query($id_nenmau) {
        $dieukien = "nenmau_id='" . $id_nenmau . "' and giabo_status='1'";
        $this->db->select("*")->from("mau_giabo")->where($dieukien);
        $i = 0;
        foreach ($this->column1 as $item) {
            $tukhoa = trim(@$_POST['search']['value']);
            if ($tukhoa) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $tukhoa);
                } else {
                    $this->db->or_like($item, $tukhoa);
                }
                if (count($this->column1) - 1 == $i)
                    $this->db->group_end();
            }
            $column1[$i] = $item;
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($column1[$_POST['order']['0']['column1']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order1)) {
            $order = $this->order1;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function danhsach_dongia($id_nenmau) {
        $this->get_datatables_query($id_nenmau);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function dongia_count_filtered($id_nenmau) {
        $this->get_datatables_query($id_nenmau);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function dongia_count_all($id_nenmau) {
        $dieukien = "nenmau_id='" . $id_nenmau . "' and giabo_status='1'";
        $this->db->select("*")->from("mau_giabo")->where($dieukien);
        return $this->db->count_all_results();
    }

    function all_nenmau() {
        $dulieu = $this->db->select("*")->from($this->table)->where("nenmau_status", "1")->get();
        return $dulieu->result();
    }

    function add_bogia($data) {
        $dieukien = array(
            "giabo_code" => $data['giabo_code'],
            "giabo_status" => '1'
        );
        $kiemtra = $this->db->select("*")->from("mau_giabo")->where($dieukien)->get();
        if ($kiemtra->num_rows() > 0) {
            return false;
        } else {
            $insert = array(
                'nenmau_id' => $data['nenmau_id'],
                'giabo_code' => $data['giabo_code']
            );
            $this->db->insert("mau_giabo", $insert);
            return TRUE;
        }
    }

    function xoa_bogia($id) {
        $update = array(
            'giabo_status' => '2'
        );
        $this->db->where("giabo_id", $id);
        return $this->db->update("mau_giabo", $update);
    }

    function sua_bogia($data) {
        $dieukien = array(
            "giabo_code" => $data['giabo_code'],
            "giabo_status" => '1'
        );
        $kiemtra = $this->db->select("*")->from("mau_giabo")->where($dieukien)->get();
        if ($kiemtra->num_rows() > 0) {
            return false;
        } else {
            $update = array(
                'giabo_code' => $data['giabo_code']
            );
            $this->db->where("giabo_id", $data['giabo_id']);
            $this->db->update("mau_giabo", $update);
            return TRUE;
        }
    }

    function danhsach_nenmau() {
        $dulieu = $this->db->select("*")->from("mau_nenmau")->where("nenmau_status", "1")->get();
        return $dulieu->result();
    }

    function _capbac_nenmau($ref, $name) {
        $chia = explode("-", $ref);
        $dulieu = "";
        for ($i = 0; $i < count($chia); $i++) {
            if ($chia[$i] != "") {
                $giatri = $this->db->select("nenmau_name,nenmau_id")->from($this->table)->where("nenmau_id", $chia[$i])->get()->result();
                if ($dulieu == "") {
                    $dulieu .= '<a href="' . base_url() . 'nenmau/chitieu?nenmau=' . $giatri[0]->nenmau_id . '">' . $giatri[0]->nenmau_name . '</a>';
                } else {
                    $dulieu .= ' &rightarrow; <a href="' . base_url() . 'nenmau/chitieu?nenmau=' . $giatri[0]->nenmau_id . '">' . $giatri[0]->nenmau_name . '</a>';
                }
            }
        }
        if (count($chia) > 2) {
            $dulieu = $dulieu . ' &rightarrow; ' . $name . '</a>';
        } else {
            $dulieu = $name;
        }
        return $dulieu;
    }
    function _Getdieukienluu($name) {
        $this->db->select('dieukienluu_id, dieukienluu_name');
        $this->db->from('mau_dieukienluu');
        $this->db->like('dieukienluu_name', $name);
        $this->db->where('dieukienluu_status', 1);
        $query = $this->db->get();
        $result = $query->result_array();
        $query->free_result();
        return ($result) ? $result : FALSE;
    }
}
