<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Congnhan extends ADMIN_Controller {

    private $privcongnhan;

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('congnhan');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->privcongnhan = $this->permarr[_PTN_CONGNHAN];
        $this->parser->assign('privcongnhan', $this->privcongnhan);
        $this->load->model('mod_congnhan');
        $this->load->model('mod_file');
    }

    function index() {
        if (!$this->privcongnhan['read']) redirect(site_url().'admin/denied?w=read');
        $validate = $this->input->get('validate');
        $this->parser->assign('validate', $validate);
        $this->parser->parse('congnhan/list');
    }

    function ajax_list() {
        $ngonngu = $this->lang->getLang();
        $list = $this->mod_congnhan->get_datatables();
        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $item->congnhan_name;
            $row[] = $item->congnhan_sign;
            $today = date("Y-m-d");
            $another_date = $item->congnhan_dateend;
            if (strtotime($today) < strtotime($another_date) || strtotime($today) == strtotime($another_date)) {
                $ngayhethan = $ngayhethan1 = date('d-m-Y', strtotime($item->congnhan_dateend));
            } else {
                $ngayhethan = date('d-m-Y', strtotime($item->congnhan_dateend));
                $ngayhethan1 = '<b style="color:red;text-decoration: line-through;" data-toggle="tooltip" title="Hết Hạn!">' . $ngayhethan . '</b>';
            }
            $row[] = $ngayhethan1;
            /*$this->curl->create($this->_api['general'].'get_file');
            $this->curl->post(array(
                'file_id' => $item->congnhan_logo
            ));
            $file = json_decode($this->curl->execute(), TRUE);*/
            $file = $this->mod_file->getFileById($item->congnhan_logo);
            $pathFile = _UPLOADS_PATH . 'congnhan/' . $file->file_name;
            $row[] = '<ul class="ace-thumbnails clearfix">
                <li style="border:none;float:none;">
                <a class="colorbox_review cboxElement" href="' . site_url() . $pathFile . '">
                <center><img width="50" height="50" alt="50x50" src="' . site_url() . $pathFile . '"></center>
                <div class="text">
                    <div class="inner" >
                        <i class="ace-icon fa fa-search-plus"></i>
                    </div>
                </div>
                </a>
                </li>
            </ul>';
            $button = '';
            $button .= ($this->privcongnhan['update']) ? ' <button class="btn btn-xs btn-info" onclick="_sua(' . $item->congnhan_id . ',\'' . $item->congnhan_name . '\',\'' . $item->congnhan_sign . '\',\'' . $ngayhethan . '\',\'' . $item->congnhan_logo . '\',\'' . $file->file_name . '\')" data-toggle="tooltip" title="'.$ngonngu['tooltip_sua'].'"><i class="ace-icon fa fa-pencil bigger-120"></i></button>' : '';
            $button .= ($this->privcongnhan['delete']) ? ' <button class="btn btn-xs btn-danger" onclick="_xoa(' . $item->congnhan_id . ')" data-toggle="tooltip" title="'.$ngonngu['tooltip_xoa'].'"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>' : '';
            $row[] = '<div style="text-align:center">' . $button . '</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_congnhan->count_all(),
            "recordsFiltered" => $this->mod_congnhan->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    function add_congnhan(){
        $current_user = $this->session->userdata("ssAdminId");
        $items = $this->input->post();
        $trungsign = $this->mod_congnhan->trungsign($items);
        if(count($trungsign) > 0){
            redirect(site_url() . 'nenmau/congnhan?validate=1');
        }
        $name_file = $_FILES["congnhan"]["name"];
        $ext = end((explode(".", $name_file)));
        $fileNameTable = uniqid() . "_" . time() . "." . $ext;
        $config['upload_path'] = _UPLOADS_PATH . 'congnhan/';
        //$config['upload_path'] = '_uploads/' . 'congnhan/';
        $config['allowed_types'] = 'png|jpg|image/gif|jpeg';
        $config['file_name'] = $fileNameTable;
        $file_id = NULL;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('congnhan'))
        {
            if(!isset($items["id_sua"])){
                $error = array('error' => $this->upload->display_errors());
                var_dump($error);
                exit(1);
            }
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $file_data = array(
                "file_name" => $fileNameTable,
                //"file_path" => _UPLOADS_PATH . 'nenmau/' . $fileNameTable,
                "nhansu_id" => $current_user,
                "create_date" => date("Y-m-d"),
                "update_date" => date("Y-m-d")
            );
            $file_id = $this->mod_file->_create($file_data);
        }
        $items["file_id"] = $file_id;
        if($items["id_sua"]){
            $ngayketthuc = $items['ngayhethan'];
            $ngay = explode("-", $ngayketthuc);
            $ngayformat = "";
            for ($i = (count($ngay) - 1); $i >= 0; $i--) {
                if ($i == 0) {
                    $ngayformat = $ngayformat . $ngay[$i];
                } else {
                    $ngayformat = $ngayformat . $ngay[$i] . "-";
                }
            }
            $data = array(
                'congnhan_id' => $items['id_sua'],
                'congnhan_name' => $items['name'],
                'congnhan_sign' => $items['kihieu'],
                'congnhan_dateend' => $ngayformat,
            );
            if($items["file_id"]){
                $data['congnhan_logo'] = $items["file_id"];
            }    
            $kiemtra = $this->mod_congnhan->suacongnhan($data);
        }else{
            $kiemtra = $this->mod_congnhan->_create($items);
        }
        return redirect(site_url() . 'nenmau/congnhan/');
    }
    function ajax_add() {
        $items = $this->input->post();
        $kiemtra = $this->mod_congnhan->_create($items);
        if ($kiemtra == true) {
            echo json_encode(array("status" => 'thanhcong'));
        } else {
            echo json_encode(array("status" => 'denied'));
        }
    }

    function xoacongnhan() {
        $id_congnhan = $this->input->post("idcongnhanxoa");
        $this->mod_congnhan->xoacongnhan($id_congnhan);
        echo "1";
    }

    function suacongnhan() {
        $dulieu = $this->input->post();
        $ngayketthuc = $dulieu['ngayhethan'];
        $ngay = explode("-", $ngayketthuc);
        $ngayformat = "";
        for ($i = (count($ngay) - 1); $i >= 0; $i--) {
            if ($i == 0) {
                $ngayformat = $ngayformat . $ngay[$i];
            } else {
                $ngayformat = $ngayformat . $ngay[$i] . "-";
            }
        }
        $data = array(
            'congnhan_id' => $dulieu['id_sua'],
            'congnhan_name' => $dulieu['name'],
            'congnhan_sign' => $dulieu['kihieu'],
            'congnhan_logo' => $dulieu['hopdong'],
            'congnhan_dateend' => $ngayformat,
        );
        $kiemtra = $this->mod_congnhan->suacongnhan($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

}
