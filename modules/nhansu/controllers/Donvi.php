<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Donvi extends ADMIN_Controller {
    private $privcheck;
    private $dsreview;
    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('donvi');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcheck = $this->permarr[_TOCHUC_DONVI];
        $this->parser->assign('privcheck', $this->privcheck);
        $this->load->model('mod_donvi');
        $this->load->model('mod_nhansu');
    }
    function index() {
        if (!$this->privcheck['read']) redirect(site_url() . 'admin/denied?w=read');
        $id = $this->input->get('id');
        $this->parser->assign('id', $id ? $id : 0);
        $this->mod_donvi->_levelUps($id, 0);
        $this->parser->assign('listUps', $this->mod_donvi->listUps);
        $this->parser->parse('donvi/list');
    }
    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $parent = $this->input->get('id');
        if ($parent) $this->mod_donvi->parent = $parent;
        $list = $this->mod_donvi->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $donvi) {
            $no++;
            $row = array();
            $row[] = "<div class=\"tendv\" data-id=\"".$donvi->donvi_id."\"><a href='" . site_url() . "nhansu/donvi?id=" . $donvi->donvi_id . "'>" . $donvi->donvi_ten . "</a></div>";
            $chucvus = $this->mod_donvi->_getChucvus($donvi->donvi_id);
            $list_chucvu = '<tr><td><strong>Tên</strong></td><td colspan="2"><strong>Số lượng</strong></td></tr>';
            foreach ($chucvus as $chucvu) {
                $list_chucvu .= "
                    <tr id=\"cv_".$donvi->donvi_id."_{$chucvu['id']}\">
                    <td><div class=\"tencv\">{$chucvu['ten']}</div></td>";
                    if($chucvu['soluong']  == '') {
                        $list_chucvu .= "<td><div class=\"sluongcv\" data-id=\"{$chucvu['id']}\">0</div><button class='btn btn-warning btn-xs updateSL' value='{$chucvu['id']}'><i class='ace-icon fa fa-floppy-o bigger-160'></i></button></td>";
                    }
                    else {
                    	$list_chucvu .= "<td><div class=\"sluongcv\" data-id=\"{$chucvu['id']}\">{$chucvu['soluong']}</div><button class='btn btn-warning btn-xs updateSL' value='{$chucvu['id']}'><i class='ace-icon fa fa-floppy-o bigger-160'></i></button></td>";
                    }
                    $list_chucvu .= "<td><button class=\"btn btn-minier btn-danger delcv\"><i class=\"ace-icon fa fa-trash-o bigger-110\"></i></button></td>
                    </tr>
                ";
            }
            $row[] = "
                <table class=\"table\">{$list_chucvu}</table>
		<div class=\"addcv\" data-id=\"".$donvi->donvi_id."\"><button class=\"btn btn-xs btn-primary addchucvu\" id=\"themcv_".$donvi->donvi_id."\"><i class=\"ace-icon fa fa-plus\"></i>Thêm chức vụ</button></div>
            ";
            $button = '';
			
            $button .= '<button class="btn btn-minier btn-warning view" data-toggle="tooltip" title="Xem"><i class="ace-icon fa fa-eye bigger-110"></i></button>';
            $button .= ($this->privcheck['update']) ? ' <button class="btn btn-minier btn-info" onclick="_sua(' . $donvi->donvi_id . ',\'' . $donvi->donvi_ten . '\',\'' . $donvi->donvi_type . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-110"></i></button>' : '';
            $button .= ($this->privcheck['delete']) ? ' <button class="btn btn-minier btn-danger" onclick="_xoa(' . $donvi->donvi_id . ',\'' . $donvi->donvi_ten . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-110"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_donvi->count_all(),
            "recordsFiltered" => $this->mod_donvi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    function ajax_add() {
        $items = $this->input->post();
        if ($items['parent'] != 0) {
            $ref = $this->mod_donvi->_getRef($items['parent']);
            $items['ref'] = ($ref) ? $ref['ref'] . $items['parent'] . '-' : '-' . $items['parent'] . '-';
            $items['donvi_level'] = count(explode("-", $items['ref']))-1;
        }else{
            $items['ref'] = '-';
            $items['donvi_level'] = '1';
        }
        $kiemtra = $this->mod_donvi->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }
    function ajax_edit() {
        $id_donvi = $this->input->post("iddonvisua");
        $name_donvi = $this->input->post("namedonvisua");
        $loai_donvi = $this->input->post("loai_donvi");
        $data = array(
            'donvi_id' => $id_donvi,
            'donvi_ten' => $name_donvi,
            'donvi_type' => $loai_donvi
        );
        $kiemtra = $this->mod_donvi->_update($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function delete() {
        if (!$this->privcheck['delete']) redirect(site_url() . 'admin/denied?w=delete');
        $iddonvi = $this->input->post("iddonvi");
        $dulieu = $this->mod_donvi->_delete($iddonvi);
        if ($dulieu == true) {
            echo "1";
        } else {
            echo "0";
        }
    }
    function getchucvus() {
        $donvi = $this->input->post('id_donvi');
        $dulieu = $this->mod_donvi->_getChucvus($donvi);
        $this->load->model('mod_nhansu');
        $kiemtra = $this->mod_nhansu->_getsoluong_chucvu($donvi);
        $kiemtra_array = array();
        foreach ($kiemtra as $row) {
            $kiemtra_array[$row->chucvu_id] = $row->soluong;
        }
        foreach ($dulieu as $row) {
            if (!isset($kiemtra_array[$row['id']]) || (($row['soluong'] > $kiemtra_array[$row['id']]) || ($row['soluong'] == NULL))) {
                echo '<option value="' . $row['id'] . '">' . $row['ten'] . '</option>';
            }
        }
    }
    function view() {
        $id = $this->input->get('id');
        $data = $this->mod_nhansu->_GetNSDV($id);
        $this->parser->assign('data',$data);
        $this->parser->assign('tendv', $this->input->get('ten'));
        $this->parser->parse('donvi/view');
    }
}
