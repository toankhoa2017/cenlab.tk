<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
get_instance()->load->iface('NenmauInterface');

class Mod_chitieu extends MY_Model implements NenmauInterface {

    var $chitieu_table = "mau_chitieu";
    var $chitieu_nenmau = "mau_nenmau_chitieu";
    var $phuongphap_table = "mau_phuongphap";
    var $kythuat_table = "mau_kythuat";
    var $nenmau_table = "mau_nenmau";
    var $phongthinghiem_table = "phongthinghiem";
    var $donvitinh_table = "mau_donvitinh";
    var $dongia = "mau_dongia";
    var $congnhan_table = "mau_congnhan";

    function goiy_phuongphap($key) {
        $dulieu = $this->db->select("*")->from($this->phuongphap_table)->where('phuongphap_status', '1')->like('phuongphap_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function goiy_kythuat($key) {
        $dulieu = $this->db->select("*")->from($this->kythuat_table)->where('kythuat_status', '1')->like('kythuat_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function goiy_chitieu($key) {
        $dulieu = $this->db->select("*")->from("mau_chitieu")->where('chitieu_status', '1')->like('chitieu_name', $key)->get();
        if ($dulieu->num_rows() > 0) {
            return $dulieu->result();
        } else {
            return false;
        }
    }

    function danhsachnenmau() {
        $dieukien = array(
            'nenmau_status' => '1',
        );
        return $this->db->select('*')->from($this->nenmau_table)->where($dieukien)->get()->result();
    }

    function donvi() {
        $dieukien = array(
            'donvitinh_status' => '1',
            'donvitinh_type' => '1'
        );
        $dulieu = $this->db->select("*")->from($this->donvitinh_table)->where($dieukien)->get();
        return $dulieu->result();
    }

    function congnhan() {
        $dulieu = $this->db->select("*")->from($this->congnhan_table)->where("congnhan_dateend >= CURDATE() and congnhan_status=1")->get();
        return $dulieu->result();
    }

    function themphuongphap($tenphuongphap) {
        $kiemtra = $this->db->select("*")->from($this->phuongphap_table)->where("phuongphap_name", $tenphuongphap)->get();
        if ($kiemtra->num_rows() == 0) {
            $phuongphap = array(
                'phuongphap_name' => $tenphuongphap,
                'phuongphap_describe' => '',
            );
            $this->db->insert($this->phuongphap_table, $phuongphap);
            return $this->db->insert_id();
        } else {
            $dulieu = $kiemtra->result();
            return $dulieu[0]->phuongphap_id;
        }
    }

    function themkythuat($tenkythuat) {
        $kiemtra = $this->db->select("*")->from($this->kythuat_table)->where("kythuat_name", $tenkythuat)->get();
        if ($kiemtra->num_rows() == 0) {
            $kythuat = array(
                'kythuat_name' => $tenkythuat,
                'kythuat_describe' => '',
                'kythuat_status' => '1'
            );
            $this->db->insert($this->kythuat_table, $kythuat);
            return $this->db->insert_id();
        } else {
            $dulieu = $kiemtra->result();
            return $dulieu[0]->kythuat_id;
        }
    }

    function themchitieu($tenchitieu, $tenchitieu_eng, $mota, $nenmau_id, $time_luu) {
        $kiemtra = $this->db->select("*")->from($this->chitieu_table)->where("chitieu_name", $tenchitieu)->get();
        if ($kiemtra->num_rows() == 0) {
            $chitieu = array(
                'chitieu_name' => $tenchitieu,
                'chitieu_describe' => $mota,
                'chitieu_name_eng' => $tenchitieu_eng
            );
            $this->db->insert($this->chitieu_table, $chitieu);
            $chitieu_id = $this->db->insert_id();
        } else {
            $this->db->where("chitieu_name", $tenchitieu);
            $this->db->update($this->chitieu_table, array('chitieu_status' => '1'));
            $id = $kiemtra->result();
            $chitieu_id = $id[0]->chitieu_id;
        }
        $check = array(
            'nenmau_id' => $nenmau_id,
            'chitieu_id' => $chitieu_id,
        );
        $kiemtra = $this->db->select("*")->from($this->chitieu_nenmau)->where($check)->get();
        if ($kiemtra->num_rows() == 0) {
            $nenmau_chitieu = array(
                'nenmau_id' => $nenmau_id,
                'chitieu_id' => $chitieu_id,
                'thoigian' => $time_luu
            );
            $this->db->insert($this->chitieu_nenmau, $nenmau_chitieu);
        }
        return $chitieu_id;
    }

    function them_dongia($nenmau_id, $chitieu_id, $phuongphap_id, $kythuat_id, $phongthinghiem_id, $donvi_id, $thoigian) {
        $kiemtra1 = array(
            'nenmau_id' => $nenmau_id,
            'chitieu_id' => $chitieu_id,
            'phuongphap_id' => $phuongphap_id,
            'kythuat_id' => $kythuat_id,
        );
        $kiemtra = $this->db->select("*")->from($this->dongia)->where($kiemtra1)->get();
        if ($kiemtra->num_rows() > 0) {
            $dongia_id = $kiemtra->row();
            if ($dongia_id->package_status == '2') {
                $giatri = array(
                    'donvitinh_id' => $donvi_id,
                    'price' => '0',
                    'thoigian' => $thoigian,
                    'package_status' => '1'
                );
                $this->db->update($this->dongia, $giatri);
                return $dongia_id->dongia_id;
            } else {
                return false;
            }
        } else {
            $sodong = $this->db->select('max(dongia_id) as idcaonhat')->from('mau_dongia')->get()->result();
            $ma_code = str_pad(((int) $sodong[0]->idcaonhat + 1), 4, "0", STR_PAD_LEFT);
            $giatri = array(
                'package_code' => 'BO_' . $ma_code,
                'nenmau_id' => $nenmau_id,
                'chitieu_id' => $chitieu_id,
                'phuongphap_id' => $phuongphap_id,
                'kythuat_id' => $kythuat_id,
                'donvi_id' => $phongthinghiem_id,
                'donvitinh_id' => $donvi_id,
                'price' => '0',
                'thoigian' => $thoigian
            );
            return $this->db->insert($this->dongia, $giatri);
        }
    }

    function xoa_chitieu($data) {
        $update_chitieu = array(
            'chitieu_status' => '2'
        );
        $this->db->where('chitieu_id', $data['chitieu_id']);
        $this->db->update($this->chitieu_table, $update_chitieu);
        $this->db->where($data);
        $this->db->update('mau_dongia', array('package_status' => '2'));
        $this->db->where($data);
        $this->db->delete($this->chitieu_nenmau);
    }

    function capnhat_gia($gia, $chitieu, $nenmau) {
        $dieukien = array(
            "chitieu_id" => $chitieu,
            "nenmau_id" => $nenmau
        );
        $update = array(
            "price" => $gia
        );
        $this->db->where($dieukien);
        $this->db->update($this->dongia, $update);
    }

    function get_chitieu($chitieu_id, $nenmau_id, $package_code) {
        $dieukien = "a.package_code='" . $package_code . "' and a.chitieu_id='" . $chitieu_id . "' and a.nenmau_id='" . $nenmau_id . "' and a.kythuat_id=d.kythuat_id and a.chitieu_id=b.chitieu_id";
        $dulieu = $this->db->select("*")->from("mau_dongia as a, mau_chitieu as b, mau_kythuat as d")->where($dieukien)->join('mau_phuongphap', 'mau_phuongphap.phuongphap_id = a.phuongphap_id', 'left')->get();
        return $dulieu->result();
    }

    function kiemtra_phuongphap($name_phuongphap, $id_phuongphap) {
        $dieukien = array(
            'phuongphap_name' => $name_phuongphap,
            'phuongphap_id' => $id_phuongphap
        );
        $dulieu = $this->db->select("*")->from($this->phuongphap_table)->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return $id_phuongphap;
        } else {
            $insert = array(
                'phuongphap_name' => $name_phuongphap
            );
            $this->db->insert($this->phuongphap_table, $insert);
            return $this->db->insert_id();
        }
    }

    function kiemtra_kythuat($name_kythuat, $id_kythuat) {
        $dieukien = array(
            'kythuat_name' => $name_kythuat,
            'kythuat_id' => $id_kythuat
        );
        $dulieu = $this->db->select("*")->from($this->kythuat_table)->where($dieukien)->get();
        if ($dulieu->num_rows() > 0) {
            return $id_kythuat;
        } else {
            $insert = array(
                'kythuat_name' => $name_kythuat,
            );
            $this->db->insert($this->kythuat_table, $insert);
            return $this->db->insert_id();
        }
    }

    function kiemtra_chitieu($idcu, $tenchitieu, $tenchitieu_eng, $mota) {
        $dieukien = array(
            "chitieu_id" => $idcu,
        );
        $data = array(
            "chitieu_name" => $tenchitieu,
            "chitieu_describe" => $mota,
            "chitieu_name_eng" => $tenchitieu_eng
        );
        $this->db->where($dieukien);
        $this->db->update($this->chitieu_table, $data);
    }

    function update_chitieu($data) {
        $dieukien = array(
            "package_code" => $data['package_code'],
        );
        $this->db->where($dieukien);
        return $this->db->update($this->dongia, $data);
    }

    var $table_file = "mau_dongia as a,mau_nenmau as b, mau_chitieu as c, mau_kythuat as e, mau_donvitinh as w , mau_nenmau_chitieu as q";
    var $table_select = "*,a.donvi_id as donvi_nhansu,a.thoigian as thoigianthuchien,q.thoigian as thoigianluumau,q.thoigian as thoigianluumau";
    var $column = array('a.package_code', 'b.nenmau_name', 'c.chitieu_name', 'e.kythuat_name');

    private function get_datatables_query($id_nenmau, $getall = FALSE) {
        if ($getall) {
            $dieukien = "a.nenmau_id=b.nenmau_id and a.chitieu_id=c.chitieu_id and a.kythuat_id=e.kythuat_id and a.donvitinh_id=w.donvitinh_id and b.nenmau_status='1' and c.chitieu_status='1' and e.kythuat_status='1' and w.donvitinh_status='1' and b.nenmau_id=q.nenmau_id and c.chitieu_id=q.chitieu_id and a.nenmau_id=" . $id_nenmau; // and a.donvi_id=q.donvi_id             
        } else {
            $dieukien = "a.donvi_id='" . $this->session->userdata('ssAdminDonvi') . "' and  a.nenmau_id=b.nenmau_id and a.chitieu_id=c.chitieu_id and a.kythuat_id=e.kythuat_id and a.donvitinh_id=w.donvitinh_id and b.nenmau_status='1' and c.chitieu_status='1' and e.kythuat_status='1' and w.donvitinh_status='1' and b.nenmau_id=q.nenmau_id and c.chitieu_id=q.chitieu_id and a.nenmau_id=" . $id_nenmau; // and a.donvi_id=q.donvi_id 
        }
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien)->join('mau_phuongphap', 'mau_phuongphap.phuongphap_id = a.phuongphap_id', 'left')->order_by("a.package_code", "desc");
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

    function danhsach_chitieu($id_nenmau, $getall = FALSE) {
        $this->get_datatables_query($id_nenmau, $getall);
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    function count_filtered($id_nenmau, $getall = FALSE) {
        $this->get_datatables_query($id_nenmau, $getall);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all($id_nenmau, $getall = FALSE) {
        if ($getall) {
            $dieukien = "a.nenmau_id=b.nenmau_id and a.chitieu_id=c.chitieu_id and a.kythuat_id=e.kythuat_id and a.donvitinh_id=w.donvitinh_id and b.nenmau_status='1' and c.chitieu_status='1' and e.kythuat_status='1' and w.donvitinh_status='1' and b.nenmau_id=q.nenmau_id and c.chitieu_id=q.chitieu_id and a.nenmau_id=" . $id_nenmau; // and a.donvi_id=q.donvi_id             
        } else {
            $dieukien = "a.donvi_id='" . $this->session->userdata('ssAdminDonvi') . "' and  a.nenmau_id=b.nenmau_id and a.chitieu_id=c.chitieu_id and a.kythuat_id=e.kythuat_id and a.donvitinh_id=w.donvitinh_id and b.nenmau_status='1' and c.chitieu_status='1' and e.kythuat_status='1' and w.donvitinh_status='1' and b.nenmau_id=q.nenmau_id and c.chitieu_id=q.chitieu_id and a.nenmau_id=" . $id_nenmau; // and a.donvi_id=q.donvi_id 
        }
        $this->db->select($this->table_select)->from($this->table_file)->where($dieukien);
        return $this->db->count_all_results();
    }

    function phuongphap() {
        $dulieu = $this->db->select("*")->from($this->phuongphap_table)->where("phuongphap_status", "1")->get();
        return $dulieu->result();
    }

    function update_thoigianluu($chitieu_id, $nenmau_id, $thoigian) {
        $dieukien = array(
            'nenmau_id' => $nenmau_id,
            'chitieu_id' => $chitieu_id,
        );
        $update = array(
            'thoigian' => $thoigian
        );
        $this->db->where($dieukien);
        $this->db->update($this->chitieu_nenmau, $update);
    }

    function info_nenmau($nenmau_id) {
        $dulieu = $this->db->select("*")->from($this->nenmau_table)->where("nenmau_id", $nenmau_id)->get();
        return $dulieu->result();
    }

    function _capbac_nenmau($ref, $name) {
        $chia = explode("-", $ref);
        $dulieu = "";
        for ($i = 0; $i < count($chia); $i++) {
            if ($chia[$i] != "") {
                $giatri = $this->db->select("nenmau_name,nenmau_id")->from($this->nenmau_table)->where("nenmau_id", $chia[$i])->get()->result();
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
            $dulieu = '<a href="' . base_url() . 'nenmau">' . $name . '</a>';
        }
        return $dulieu;
    }

}
