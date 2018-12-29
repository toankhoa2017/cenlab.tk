<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nhansu extends ADMIN_Controller {
    private $privcheck;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('nhansu');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_TOCHUC_NHANSU];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_nhansu');
    }
    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $this->parser->parse('nhansu/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_nhansu->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $nhansu) {
            $no++;
            $row = array();
            $row[] = "<a href='" . site_url() . "nhansu/detail?id=" . $nhansu->nhansu_id . "'>" . $nhansu->nhansu_lastname . " " . $nhansu->nhansu_firstname . "</a>";
            $row[] = date('d-m-Y', strtotime($nhansu->nhansu_birthday));
            $row[] = $nhansu->nhansu_email;
            $row[] = $nhansu->nhansu_phone;
            $row[] = $nhansu->nhansu_address;
            $button = '';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-minier btn-info"  onclick="_sua(' . $nhansu->nhansu_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-110"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-minier btn-danger" onclick="_xoa(' . $nhansu->nhansu_id . ',\'' . $nhansu->nhansu_lastname . " " . $nhansu->nhansu_firstname . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-110"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_nhansu->count_all(),
            "recordsFiltered" => $this->mod_nhansu->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    function get() {
        $id_nhansu = $this->input->post("id_nhansu");
        $dulieu = $this->mod_nhansu->_get($id_nhansu);
        echo json_encode($dulieu);
    }
    function add() {
        if (!$this->privcheck['write']) redirect(site_url() . 'admin/denied?w=read');
        $this->load->model('mod_loaihopdong');
        $listloai = $this->mod_loaihopdong->_gets();
        $loaihopdong = array();
        foreach ($listloai as $lhd) {
            $loaihopdong[$lhd->loaihopdong_id] = $lhd->loaihopdong_ten;
        }
        $this->parser->assign('loaihopdong', $loaihopdong);
        
        $this->load->model('mod_donvi');
        $listdonvi = $this->mod_donvi->_gets();
        $donvi = array();
        foreach ($listdonvi as $dv) {
            $donvi[$dv->donvi_id] = $dv->donvi_ten;
        }
        $this->parser->assign('donvi', $donvi);
        $this->parser->parse('nhansu/add');
    }
    function ajax_add() {
        $dulieu = $this->input->post();
        $this->curl->create($this->_api['account']['register']);
        $this->curl->post(array(
            'email' => $dulieu['email'],
            'phone' => $dulieu['phone'],
            'cmnd' => $dulieu['cmnd'],
            'project' => _PROJECT_ID
        ));
        $login = json_decode($this->curl->execute());
        if (isset($login->err_code) && $login->err_code == 200) {
            $name_file = $_FILES["hopdong"]["name"];
            $ext = end((explode(".", $name_file)));
            $fileNameTable = uniqid() . "_" . time() . "." . $ext;
            $config['upload_path'] = _UPLOADS_PATH . 'nhansu/';
            $config['allowed_types'] = '*';
            $config['file_name'] = $fileNameTable;
            $file_id = NULL;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('hopdong')) {
                $error = array('error' => $this->upload->display_errors());
            }
            else {
                $data = array('upload_data' => $this->upload->data());
                $this->load->model('mod_file');
                $file_id = $this->mod_file->_create(array(
                    'file_name' => $fileNameTable
                ));
            }
            //Tao nhan su
            $ngay = explode("-", $dulieu['ngaysinh']);
            $birthday = "";
            for ($i = (count($ngay) - 1); $i >= 0; $i--) {
                if ($i == 0) {
                    $birthday = $birthday . $ngay[$i];
                } else {
                    $birthday = $birthday . $ngay[$i] . "-";
                }
            }
            $nhansu_id = $this->mod_nhansu->_create(array(
                'nhansu_lastname' => $dulieu['ho'],
                'nhansu_firstname' => $dulieu['ten'],
                'nhansu_email' => $dulieu['email'],
                'nhansu_phone' => $dulieu['phone'],
                'nhansu_cmnd' => $dulieu['cmnd'],
                'nhansu_birthday' => $birthday,
                'nhansu_address' => $dulieu['diachi'],
                'nhansu_password' => $login->pwd,
		'account_id' => $login->id
            ));
            //Tao hop dong
            $ngaybd = $this->input->post('ngaybatdau');
            $ngay = explode("-", $ngaybd);
            $ngaybatdau = "";
            for ($i = (count($ngay) - 1); $i >= 0; $i--) {
                if ($i == 0) {
                    $ngaybatdau = $ngaybatdau . $ngay[$i];
                } else {
                    $ngaybatdau = $ngaybatdau . $ngay[$i] . "-";
                }
            }
            $ngaykt = $this->input->post('ngayketthuc');
            $ngay = explode("-", $ngaykt);
            $ngayketthuc = "";
            for ($i = (count($ngay) - 1); $i >= 0; $i--) {
                if ($i == 0) {
                    $ngayketthuc = $ngayketthuc . $ngay[$i];
                } else {
                    $ngayketthuc = $ngayketthuc . $ngay[$i] . "-";
                }
            }
            if ($this->mod_nhansu->_thaydoi(array(
                'hopdong_note' => $this->input->post('notehopdong'),
                'nhansu_id' => $nhansu_id,
                'loaihopdong_id' => $this->input->post('loaihopdong'),
                'hopdong_datestart' => $ngaybatdau,
                'hopdong_dateend' => $ngayketthuc,
                'donvi_id' => $this->input->post('donvi'),
                'chucvu_id' => $this->input->post('chucvu'),
                'file_id' => $file_id
            ))) echo '200';
            else echo '201';
        } else {
            if ($login->err_code == 101) {
                echo "101";
            }
            if ($login->err_code == 102) {
                echo "102";
            }
            if ($login->err_code == 103) {
                echo "103";
            }
        }
    }
    function update() {
        if (!$this->privcheck['update']) redirect(site_url() . 'admin/denied?w=update');
        $ngaysinh = $this->input->post('ngaysinh');
        $ngay = explode("-", $ngaysinh);
        $ngayformat = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayformat = $ngayformat . $ngay[$i];
            } else {
                $ngayformat = $ngayformat . $ngay[$i] . "-";
            }
        }
        $data = array(
            'nhansu_id' => $this->input->post('id'),
            'nhansu_lastname' => $this->input->post('ho'),
            'nhansu_firstname' => $this->input->post('ten'),
            'nhansu_birthday' => $ngayformat,
            'nhansu_address' => $this->input->post('diachi')
        );
        $dulieu = $this->mod_nhansu->_update($data);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function delete() {
        if (!$this->privcheck['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $id_nhansu = $this->input->post("idnhansuxoa");
        $dulieu = $this->mod_nhansu->_delete($id_nhansu);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function check_unique() {
        $dulieu = $this->input->post();
        $this->curl->create($this->_api['account']['check'][$dulieu['field']]);
        $this->curl->post(array(
            'val' => $dulieu['val'],
            'project' => _PROJECT_ID
        ));
        $check_unique = json_decode($this->curl->execute());
        if (isset($check_unique->err_code) && $check_unique->err_code == 200) {
            echo "1";
        } else {
            echo $check_unique->message;
        }
    }
    function detail() {
        $id = $this->input->get('id');
        $data = $this->mod_nhansu->_get($id);
        $this->load->model('mod_hopdong');
        $hopdongs = $this->mod_hopdong->_getdanhsachhdong($id);
        $this->load->model('mod_loaihopdong');
        $listloai = $this->mod_loaihopdong->_gets();
        $loaihopdong = array();
        foreach ($listloai as $lhd) {
            $loaihopdong[$lhd->loaihopdong_id] = $lhd->loaihopdong_ten;
        }
        $this->parser->assign('loaihopdong', $loaihopdong);
        $this->load->model('mod_donvi');
        $listdonvi = $this->mod_donvi->_gets();
        $donvis = array();
        foreach ($listdonvi as $dv) {
            $donvis[$dv->donvi_id] = $dv->donvi_ten;
        }
        $this->parser->assign('donvis', $donvis);
        $this->parser->assign('profile', $data);
        $this->parser->assign('hopdongs', $hopdongs);
        $this->parser->parse('nhansu/detail');
    }
    function review() {
        $id_nhansu = $this->input->post('idnhansu');
        $dulieu = $this->mod_nhansu->_review($id_nhansu);
        $this->parser->assign('hopdong', $dulieu);
        $this->parser->parse('nhansu/review');
    }
    function thaydoi() {
        $id_nhansu = $this->input->post('idnhansu');
        $data = $this->mod_nhansu->_get($id_nhansu);
        
        $this->load->model('mod_loaihopdong');
        $listloai = $this->mod_loaihopdong->_gets();
        $loaihopdong = array();
        foreach ($listloai as $lhd) {
            $loaihopdong[$lhd->loaihopdong_id] = $lhd->loaihopdong_ten;
        }
        $this->parser->assign('loaihopdong', $loaihopdong);
        $this->parser->assign('hopdong', $data['loaihopdong_id']);

        $this->load->model('mod_donvi');
        $listdonvi = $this->mod_donvi->_gets();
        $donvis = array();
        foreach ($listdonvi as $dv) {
            $donvis[$dv->donvi_id] = $dv->donvi_ten;
        }
        $this->parser->assign('donvis', $donvis);
        $this->parser->assign('donvi', $data['donvi_id']);        
        $listchucvu = $this->mod_donvi->_getChucvus($data['donvi_id']);
        $chucvus = array();
        foreach ($listchucvu as $cv) {
            $chucvus[$cv['id']] = $cv['ten'];
        }
        $this->parser->assign('chucvus', $chucvus);
        $this->parser->assign('chucvu', $data['chucvu_id']);        
        
        $this->parser->parse('nhansu/thaydoi');
    }
    function thaydoi_submit() {
        $ngaybd = $this->input->post('ngaybatdau');
        $ngay = explode("-", $ngaybd);
        $ngaybatdau = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngaybatdau = $ngaybatdau . $ngay[$i];
            } else {
                $ngaybatdau = $ngaybatdau . $ngay[$i] . "-";
            }
        }

        $ngaykt = $this->input->post('ngayketthuc');
        $ngay = explode("-", $ngaykt);
        $ngayketthuc = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayketthuc = $ngayketthuc . $ngay[$i];
            } else {
                $ngayketthuc = $ngayketthuc . $ngay[$i] . "-";
            }
        }
        $data = array(
            'hopdong_note' => $this->input->post('note'),
            'nhansu_id' => $this->input->post('nhansu_id'),
            'loaihopdong_id' => $this->input->post('loaihopdong'),
            'hopdong_datestart' => $ngaybatdau,
            'hopdong_dateend' => $ngayketthuc,
            'donvi_id' => $this->input->post('donvi'),
            'file_id' => $this->input->post('hopdong'),
            'chucvu_id' => $this->input->post('chucvu')
        );
        $dulieu = $this->mod_nhansu->_thaydoi($data);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function themhopdong() {    
        $name_file = $_FILES["hopdong"]["name"];
        $ext = end((explode(".", $name_file)));
        $fileNameTable = uniqid() . "_" . time() . "." . $ext;
        $config['upload_path'] = _UPLOADS_PATH . 'nhansu/';
        $config['allowed_types'] = '*';
        $config['file_name'] = $fileNameTable;
        $file_id = NULL;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('hopdong')) {
            $error = array('error' => $this->upload->display_errors());
        }
        else {
            $data = array('upload_data' => $this->upload->data());
            $this->load->model('mod_file');
            $file_id = $this->mod_file->_create(array(
                'file_name' => $fileNameTable
            ));
        }   
        $ngaybd = $this->input->post('ngaybatdau');
        $ngay = explode("-", $ngaybd);
        $ngaybatdau = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngaybatdau = $ngaybatdau . $ngay[$i];
            } else {
                $ngaybatdau = $ngaybatdau . $ngay[$i] . "-";
            }
        }
        $ngaykt = $this->input->post('ngayketthuc');
        $ngay = explode("-", $ngaykt);
        $ngayketthuc = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayketthuc = $ngayketthuc . $ngay[$i];
            } else {
                $ngayketthuc = $ngayketthuc . $ngay[$i] . "-";
            }
        }
        $data = array(
            'hopdong_note' => $this->input->post('notehopdong'),
            'nhansu_id' => $this->input->post('nhansu_id'),
            'loaihopdong_id' => $this->input->post('loaihopdong'),
            'hopdong_datestart' => $ngaybatdau,
            'hopdong_dateend' => $ngayketthuc,
            'donvi_id' => $this->input->post('donvi'),
            'chucvu_id' => $this->input->post('chucvu'),
            'file_id' => $file_id
        );
        $this->load->model('mod_hopdong');
        $dulieu = $this->mod_hopdong->_create($data);
        return redirect(site_url() . 'nhansu/detail?id=' . $this->input->post('nhansu_id'));
    }
    function phongBanChinh(){
        $hopdong_id = $this->input->post("hopdong_id");
        $nhansu_id = $this->input->post("nhansu_id");
        $result = $this->mod_nhansu->_updatePBChinh($nhansu_id, $hopdong_id);
        if($result)
            echo 1;
        else
            echo 0;
    }
    function xoahopdong() {
        $this->load->model('mod_hopdong');
        $hopdong_id = $this->input->post("hopdong_id");
        $result = $this->mod_hopdong->_delete($hopdong_id);
        if($result)
            echo 1;
        else
            echo 0;
    }
}
