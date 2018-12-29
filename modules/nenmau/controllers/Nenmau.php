<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nenmau extends ADMIN_Controller {

    private $privnenmau;
    private $privchitieu;
    private $privtheodoichitieu;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('nenmau');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privnenmau = $this->permarr[_PTN_NENMAU];
        $this->privchitieu = $this->permarr[_PTN_CHITIEU];
        $this->privdinhgia = $this->permarr[_PTN_DINHGIA];
        $this->privtheodoichitieu = $this->permarr[_PTN_QLCHITIEU];
        $this->parser->assign('privnenmau', $this->privnenmau);
        $this->parser->assign('privchitieu', $this->privchitieu);
        $this->parser->assign('privdinhgia', $this->privdinhgia);
        $this->load->model('mod_nenmau');
        $this->load->model('mod_chat');
    }

    function index() {
        if (!$this->privnenmau['read']) redirect(site_url() . 'admin/denied?w=read');
        $dongiaChats = $this->mod_chat->getGiaByDongia(0);// bo_id = 0 cho định giá nền mẫu
        $chatGias = array();
        foreach ($dongiaChats as $dongia){
            $chatGias[] = $dongia["gia_price"];
        }
        $this->parser->assign('chatGias', $chatGias);
        $this->parser->assign('package', 0); // bo_id = 0 cho định giá nền mẫu
        $this->parser->parse('nenmau/list');
    }

    function ajax_add() {
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_nenmau->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
        } else {
            $items['ref'] = '-';
        }
        $kiemtra = $this->mod_nenmau->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong', 'ds' => $items));
        } else {
            echo json_encode(array("status" => 'denied', 'ds' => $items));
        }
    }

    function dequyxoanenmau($danhsach, $id_parent) {
        $cate_child = array();
        $this->mod_nenmau->xoanenmau($id_parent);
        foreach ($danhsach as $key => $item) {
            if ($item->nenmau_idparent == $id_parent) {
                $cate_child[] = $item;
                unset($danhsach[$key]);
            }
        }
        if ($cate_child) {
            foreach ($cate_child as $key => $item) {
                $this->mod_nenmau->xoanenmau($item->nenmau_id);
                $this->dequyxoanenmau($danhsach, $item->nenmau_id);
            }
        }
    }

    function xoanenmau() {
        if (!$this->privnenmau['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $id_nenmau = $this->input->post("idnenmauxoa");
        $danhsach = $this->mod_nenmau->danhsach();
        $this->dequyxoanenmau($danhsach, $id_nenmau);
        echo "1";
    }

    function suanenmau() {
        if (!$this->privnenmau['update']) redirect(site_url() . 'admin/denied?w=update');
        $id_nenmau = $this->input->post("idnenmausua");
        $name_nenmau = $this->input->post("namenenmausua");
        $mota_nenmau = $this->input->post("motanenmausua");
        $name_truocthaydoi = $this->input->post("tennenmautruocthaydoi");
        $name_eng = $this->input->post("name_eng");
        $dieukienluu = $this->input->post("dieukienluu");
        $dieukienluu_id = $this->input->post("dieukienluu_id");
        $donvi_id = $this->input->post("donvi");
        $get_dieukienluu_id = $this->mod_nenmau->capnhat_dieukienluu($dieukienluu_id, $dieukienluu);
        $moi = array(
            'nenmau_id' => $id_nenmau,
            'dieukienluu_id' => $get_dieukienluu_id
        );
        $this->mod_nenmau->update_nenmau_dieukienluu($moi, $id_nenmau);
        $data = array(
            'nenmau_id' => $id_nenmau,
            'nenmau_name' => $name_nenmau,
            'nenmau_mota' => $mota_nenmau,
            'nenmau_name_eng' => $name_eng,
            'donvi_id' => $donvi_id
        );
        $kiemtra = $this->mod_nenmau->suanenmau($data, $name_truocthaydoi);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

    function detail($id) {
        $info = $this->mod_nenmau->info_nenmau($id);
        $nenmau = array();
        $dulieu = $this->mod_nenmau->all_nenmau();
        foreach ($dulieu as $row) {
            $nenmau[$row->nenmau_id] = $row->nenmau_name;
        }
        $this->parser->assign('nenmau', $nenmau);
        $this->parser->assign('info', $info);
        $this->parser->parse('nenmau/detail');
    }

    var $review = "";

    function dequy_getnenmau($danhsach, $id_parent, $khoangcach) {
        $kc = $khoangcach * 20;
        $khoangcach++;
        $cate_child = array();
        foreach ($danhsach as $key => $item) {
            if ($item->nenmau_idparent == $id_parent) {
                $cate_child[] = $item;
                unset($danhsach[$key]);
            }
        }
        if ($cate_child) {
            foreach ($cate_child as $key => $item) {
                $dulieu = $this->mod_nenmau->review1($item->nenmau_id);
                $this->review .= '<p style="padding-left:' . $kc . 'px">- ' . $dulieu . '</p>';
                $this->dequy_getnenmau($danhsach, $item->nenmau_id, $khoangcach);
            }
        }
    }

    function review() {
        $nenmau_id = $this->input->post('idnenmau');
        $danhsach = $this->mod_nenmau->danhsach();
        $this->dequy_getnenmau($danhsach, $nenmau_id, 0);
        echo $this->review;
    }

    function goiy_dieukienluu() {
        $tukhoa = $this->input->post('key');
        $dulieu = $this->mod_nenmau->goiy_dieukienluu($tukhoa);
        foreach ($dulieu as $row) {
            $output[] = array(
                "label" => $row->dieukienluu_name,
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }

    var $danhsach = array();
    var $mangkiemtra = array();

    function danhsach_dequy($danhsach, $id) {
        $cate_child = array();
        foreach ($danhsach as $key => $item) {
            if ($item->nenmau_idparent == $id) {
                $cate_child[] = $item;
                if (!in_array($item->nenmau_id, $this->mangkiemtra)) {
                    array_push($this->danhsach, $item);
                    array_push($this->mangkiemtra, $item->nenmau_id);
                }
                unset($danhsach[$key]);
                $this->danhsach_dequy($danhsach, $item->nenmau_id);
            }
        }
    }

    function tree_data() {
        $this->danhsach = array();
        $this->mangkiemtra = array();
        $mangkiemtra = array();
        $key123 = $this->input->post('search');
        $danhsach = $this->mod_nenmau->danhsach($key123);
        if ($key123 == "") {
            foreach ($danhsach as $key => $value) {
                if (!in_array($value->nenmau_id, $mangkiemtra)) {
                    $sub_data = array();
                    $sub_data["id"] = $value->nenmau_id;
                    $mangkiemtra[] = $value->nenmau_id;
                    $button = '';
                    $button .= ($this->privnenmau['write']) ? ' <button type="button" class="btn btn-xs btn-purple" onclick="_add(' . $value->nenmau_id . ')"><i class="ace-icon fa fa-plus"></i></button>' : '';
                    $button .= ($this->privnenmau['update']) ? ' <button type="button" class="btn btn-xs btn-info" onclick="_sua(' . $value->nenmau_id . ',\'' . $value->nenmau_name . '\',\'' . $value->nenmau_mota . '\',\'' . $value->nenmau_name_eng . '\',\'' . $value->dieukienluu_name . '\',\'' . $value->dieukienluu_id . '\',\'' . $value->donvi_id . '\')"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
                    $button .= ($this->privnenmau['delete']) ? ' <button type="button" class="btn btn-xs btn-danger" onclick="_xoa(' . $value->nenmau_id . ')"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
                    $onclick = ($this->privchitieu['read'] || $this->privtheodoichitieu['read']) ? ' onclick="hihi(' . $value->nenmau_id . ')"' : '';
                    $sub_data["text"] = '<span style="position: absolute;width:100%"' . $onclick . '>' . $value->nenmau_name . '</span><span class="pull-right action-buttons" style="position: absolute;top:4px;right:5px">' . $button . '</span>';
                    $sub_data["parent_id"] = $value->nenmau_idparent;
                    $data[] = $sub_data;
                }
            }
        } else {
            $mangloc = array();
            $toanbo = $this->mod_nenmau->danhsach();
            foreach ($danhsach as $key => $value) {
                array_push($this->danhsach, $value);
                array_push($this->mangkiemtra, $value->nenmau_name);
                $this->danhsach_dequy($toanbo, $value->nenmau_id);
            }
            $danhsach = $this->danhsach;
            foreach ($danhsach as $key => $value) {
                if (!in_array($value->nenmau_id, $mangkiemtra)) {
                    $sub_data = array();
                    $sub_data["id"] = $value->nenmau_id;
                    $mangkiemtra[] = $value->nenmau_id;
                    $button = '';
                    $button .= ($this->privnenmau['write']) ? ' <button type="button" class="btn btn-xs btn-purple" onclick="_add(' . $value->nenmau_id . ')" data-toggle="tooltip" title="Thêm&nbsp;Nền&nbsp;Mẫu"><i class="ace-icon fa fa-plus"></i></button>' : '';
                    $button .= ($this->privnenmau['update']) ? ' <button type="button" class="btn btn-xs btn-info" onclick="_sua(' . $value->nenmau_id . ',\'' . $value->nenmau_name . '\',\'' . $value->nenmau_mota . '\',\'' . $value->nenmau_name_eng . '\',\'' . $value->dieukienluu_name . '\',\'' . $value->dieukienluu_id . '\',\'' . $value->donvi_id . '\')" data-toggle="tooltip" title="Sửa"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
                    $button .= ($this->privnenmau['delete']) ? ' <button type="button" class="btn btn-xs btn-danger" onclick="_xoa(' . $value->nenmau_id . ')" data-toggle="tooltip" title="Xóa"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
                    $onclick = ($this->privchitieu['read']) ? ' onclick="hihi(' . $value->nenmau_id . ')"' : '';
                    $sub_data["text"] = '<span style="position: absolute;width:100%"' . $onclick . '>' . $value->nenmau_name . '</span><span class="pull-right action-buttons" style="position: absolute;top:4px;right:5px">' . $button . '</span>';
                    $sub_data["parent_id"] = $value->nenmau_idparent;
                    $data[] = $sub_data;
                }
            }
        }
        foreach ($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }
        foreach ($data as $key => &$value) {
            if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
                $output[$value["parent_id"]]["nodes"][] = &$value;
                unset($data[$key]);
            }
        }
        $price = array();
        foreach ($data as $key => $row) {
            $price[$key] = $row['parent_id'];
        }
        sort($price);
        array_multisort($price, SORT_ASC, $data);
        echo json_encode($data);
    }

    function load_select_nenmau() {
        $dulieu = $this->mod_nenmau->load_select_nenmau($id);
        echo "<option value='0'>Nền Mẫu Cha</option>";
        foreach ($dulieu as $row) {
            echo "<option value='" . $row->nenmau_id . "'>" . $row->nenmau_name . "</option>";
        }
    }

    function danhsach_donvi() {
        $this->curl->create($this->_api['nhansu'].'all_donvi');
        $this->curl->post(array());
        $donvi = json_decode($this->curl->execute());
        foreach ($donvi->danhsach as $row) {
            echo "<option value='" . $row->donvi_id . "'>" . $row->donvi_ten . "</option>";
        }
    }

    function danhsach_dongia() {
        $ngonngu = $this->lang->getLang();
        $id_nenmau = $this->input->post('nenmau_id');
        $list = $this->mod_nenmau->danhsach_dongia($id_nenmau);
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $row) {
            $hang = array();
            $no++;
            $hang[] = $no;
            $hang[] = '<a href="' . base_url('nenmau/giabo?gb=' . $row->giabo_id . '&nm=' . $row->nenmau_id) . '">' . $row->giabo_code . '</a>';
            $button = "";
            $button .= ' <button class="btn btn-xs btn-info" onclick="_sua_bogia(' . $row->giabo_id . ',\'' . $row->giabo_code . '\')" data-toggle="tooltip" title="'.$ngonngu['bogia_tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>';
            $button .= ' <button class="btn btn-xs btn-danger" onclick="_xoa_bogia(' . $row->giabo_id . ')" data-toggle="tooltip" title="'.$ngonngu['bogia_tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>';
            $button .= ' <button class="btn btn-xs btn-purple" onclick="add_bogia(\'' . base_url("nenmau/giabo?gb=" . $row->giabo_id . "&nm=" . $row->nenmau_id) . '\')" data-toggle="tooltip" title="'.$ngonngu['bogia_tooltip_themchat'].'"><i class="ace-icon fa fa-plus bigger-120"></i></button>';
            $hang[] = $button;
            $data[] = $hang;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_nenmau->dongia_count_all($id_nenmau),
            "recordsFiltered" => $this->mod_nenmau->dongia_count_filtered($id_nenmau),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function add_bogia() {
        $value = $this->input->post();
        $dulieu = $this->mod_nenmau->add_bogia($value);
        ($dulieu == true) ? $kq = "1" : $kq = "2";
        echo $kq;
    }

    function xoa_bogia() {
        $id = $this->input->post('id_bogia');
        $dulieu = $this->mod_nenmau->xoa_bogia($id);
        ($dulieu == true) ? $kq = "1" : $kq = "2";
        echo $kq;
    }

    function sua_bogia() {
        $value = $this->input->post();
        $dulieu = $this->mod_nenmau->sua_bogia($value);
        ($dulieu == true) ? $kq = "1" : $kq = "2";
        echo $kq;
    }

    function bogia($nenmau_id) {
        $info = $this->mod_nenmau->info_nenmau($nenmau_id);
        $capbac_nenmau = $this->mod_nenmau->_capbac_nenmau($info[0]->nenmau_ref, $info[0]->nenmau_name);
        $this->parser->assign('capbac_nenmau', $capbac_nenmau);
        $this->parser->assign('nenmau', $info);
        $this->parser->parse('giabo/bogia');
    }
    function ajax_listdieukienluu() {
        $keyword = $this->input->post('key');
        $dulieu = $this->mod_nenmau->_Getdieukienluu($keyword);
        foreach ($dulieu as $row) {
            $output[] = array(
                "label" => $row['dieukienluu_name'],
                "info" => $row,
                "category" => ""
            );
        }
        echo json_encode($output);
    }
}
