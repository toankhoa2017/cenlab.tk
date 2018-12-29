<?php

defined('BASEPATH') OR exit('No direct script access allowed');
@session_start();

class File extends MY_Controller {

    var $mausac = array('pink', 'red', 'orange', 'green');
    var $dsft = "";

    function __construct() {
        parent::__construct();
        //Language
        $this->lang->load('file');
        $this->parser->assign('languages', $this->lang->getLang());
        //End language
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('security');
        $this->load->model('mod_file');
    }

    function danhsachfile_type() {
        $ngonngu = $this->lang->getLang();
        $danhsach = $this->mod_file->danhsachftype();
        foreach ($danhsach as $key => $value) {
            $sub_data["id"] = $value->ftype_id;
            $link_forder = $this->mod_file->get_forder1($value->ftype_id);
            $forder = explode("/",$link_forder);
            $sub_data["text"] = '<i class="icon-folder ace-icon fa fa-folder red"></i> ' . $value->ftype_name . ' ('.$link_forder.')
                <div class="pull-right action-buttons" style="position: absolute;top:4px;right:5px">
                    <button type="button" class="btn btn-xs btn-info" data-toggle="tooltip" title="'.$ngonngu['danhmuc_tooltip_sua'].'" onclick="suathumuc(' . $value->ftype_id . ')">
			<i class="ace-icon fa fa-pencil bigger-130" ></i>
                    </button>
                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['danhmuc_tooltip_xoa'].'" onclick="xoathumuc(' . $value->ftype_id . ')">
			<i class="ace-icon fa fa-trash-o bigger-130"></i>
                    </button>
		</div>';
            $sub_data["parent_id"] = $value->ftype_idparent;
            $data[] = $sub_data;
        }
        foreach ($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }
        foreach ($data as $key => &$value) {
            if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
                $output[$value["parent_id"]]["nodes"][] = &$value;
            }
        }
        foreach ($data as $key => &$value) {
            if ($value["parent_id"] && isset($output[$value["parent_id"]])) {
                unset($data[$key]);
            }
        }
        echo json_encode($data);
    }

    function index() {
        $kiemtra = $this->input->get('check');
        $nhansu_id = $this->input->get('nhansu_id');
        if($nhansu_id==""){$nhansu_id=0;}
        $this->parser->assign('nhansu_id', $nhansu_id);
        if(isset($kiemtra)&&$kiemtra==1){
            $chon = 1;
        }else{
            $chon = 2;
        }
        $data = $this->mod_file->thumuccha();
        $dulieu = array();
        foreach ($data as $dt) {
            $dulieu[$dt->ftype_id] = $dt->ftype_name;
        }
        $this->parser->assign('caythumuc', $dulieu);
        
        $data = $this->mod_file->forder();
        $forder = array();
        foreach ($data as $dt) {
            $forder[$dt->file_forder_id] = $dt->file_forder_name;
        }
        $this->parser->assign('forder', $forder);

        $this->parser->assign('chon', $chon);
        $this->parser->parse('file/list');
    }

    function listfile() {
        $ngonngu = $this->lang->getLang();
        $id_file_type = $this->input->post("id_file_typee");
        $check = $this->input->post("chon");
        $list = $this->mod_file->danhsachfile($id_file_type);
        $data = array();
        foreach ($list as $item) {
            if(isset($check)&&$check=='1'){
                $chon = ' <button type="button" class="btn btn-xs btn-primary" data-toggle="tooltip" title="'.$ngonngu['danhsachfile_tooltip_chon'].'" onclick="_check(' . $item->file_id . ',\'' . $item->file_name . '\')"><i class="ace-icon fa fa-check bigger-120"></i></button>';
            }else{
                $chon = '';
            }
            $row = array();
            $row[] = '<div onclick="loadinfomation(' . $item->file_id . ')">' . $item->file_name . '<div>';
            $row[] = $item->file_date;
            $row[] = '<center>'.$chon.'
                    <button type="button" class="btn btn-xs btn-danger" data-toggle="tooltip" title="'.$ngonngu['danhsachfile_tooltip_xoa'].'" onclick="xoafile(' . $item->file_id . ')"><i class="ace-icon fa fa-trash-o bigger-120"></i></button>
                </center>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->mod_file->count_all($id_file_type),
            "recordsFiltered" => $this->mod_file->count_filtered($id_file_type),
            "data" => $data,
        );
        echo json_encode($output);
    }

    function getlink($idparent, $tenfoder) {
        if ($idparent == 0) {
            return $tenfoder;
        } else {
            $data = $idparent;
            $duongdan1 = "";
            while ($data != '0') {
                $dulieu = $this->mod_file->getlink($data);
                if ($dulieu == true) {
                    $data = $dulieu[0]->ftype_idparent;
                    $duongdan1 = $duongdan1 . $dulieu[0]->ftype_name . "/";
                } else {
                    $data = 0;
                }
            }
            $link = $this->duongdan($duongdan1);
            return $link;
        }
    }

    function duongdan($nguon) {
        $data = explode("/", $nguon);
        $duongdan = "";
        for ($i = 0; $i < (count($data) - 1); $i++) {
            if ($i == (count($data) - 2)) {
                $duongdan = $duongdan . $data[$i];
            } else {
                $duongdan = $duongdan . $data[$i] . "/";
            }
        }
        return $duongdan;
    }

    function themthumuc() {
        $tenthumuc = $this->input->post("tenthumuc1");
        $idparent = $this->input->post("idparent1");
        $idforder = $this->input->post("idforder");
        $data = array(
            ftype_name => $tenthumuc,
            ftype_idparent => $idparent,
            ftype_status => '0',
            file_forder_id => $idforder,
        );
        $kiemtra = $this->mod_file->themthumuc($data);
        if ($kiemtra == true) {
            //$duongdan = $this->getlink($idparent, $tenthumuc);
            //$directory = './_uploads/files/' . $duongdan;
            if (!is_dir($directory)) {
                //mkdir($directory, 777);
                echo "1"; //thành công
            } else {
                echo "0"; //thất bại đã tồn tại
            }
        } else {
            echo "0"; //thất bại
        }
    }

    function suathumuc() {
        $id = $this->input->post("idthumuc");
        $data = $this->mod_file->get_suathumuc($id);
        if ($data == true) {
            $dulieu = array();
            foreach ($data as $dl) {
                $dulieu['ftype_id'] = $dl->ftype_id;
                $dulieu['ftype_idparent'] = $dl->ftype_idparent;
                $dulieu['ftype_name'] = $dl->ftype_name;
                $dulieu['file_forder_id'] = $dl->file_forder_id;
            }
            echo json_encode($dulieu);
        } else {
            echo '0';
        }
    }

    function xuly_suathumuc($id) {
        $tenthumuc = $this->input->post("tenthumuc2");
        $idparent = $this->input->post("idparent2");
        $id = $this->input->post("id2");
        $idforder = $this->input->post("idforder2");
        $data = array(
            ftype_name => $tenthumuc,
            ftype_idparent => $idparent,
            ftype_id => $id,
            file_forder_id => $idforder
        );
        $kiemtra = $this->mod_file->suathumuc($data);
        if ($kiemtra == true) {
            //$duongdan = $this->getlink($idparent,$tenthumuc);
            //$directory = './_uploads/'.$duongdan;
            //rename('./_uploads/files/oke','./_uploads/files/hihi');
            echo "1";
        } else {
            echo "0"; //thất bại
        }
    }

    function xoafoder($dirPath) {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::xoafoder($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    function xoathumuc() {
        $idthumuc = $this->input->post("idthumuc");
        $xuly = $this->mod_file->xoathumuc($idthumuc);
        // $duongdan = $this->getlink($idthumuc, $tên thư mục gốc);
        // $directory = './_uploads/' . $duongdan;
        // $this->xoafoder($directory);
    }

    function xoafile() {
        $idfile = $this->input->post("idfile");
        $xuly = $this->mod_file->xoafile($idfile);
        //get link xong đưa vào xóa
        //$duongdan = $this->getlink($idfile, $tên thư mục gốc);
        //$directory = './_uploads/' . $duongdan;
        //unlink($directory);
    }

    function getSize($file) {
        $size = filesize($file);
        if ($size <= 0)
            if (!(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')) {
                $size = trim(`stat -c%s $file`);
            } else {
                $fsobj = new COM("Scripting.FileSystemObject");
                $f = $fsobj->GetFile($file);
                $size = $f->Size;
            }
        return (int) ($size / 1024); //1024 lấy ra kb
    }

    function informationfile() {
        $id = $this->input->post("idfile");
        $data = $this->mod_file->get_file($id);
        if ($data == true) {
            $dulieu = array();
            foreach ($data as $dl) {
                $dulieu['file_name'] = $dl->file_name . " <button class='btn btn-xs btn-info' data-toggle='tooltip' title='Rename' onclick='rename(" . $dl->file_id . ",\"" . $dl->file_name . "\"," . $dl->ftype_id . ")'><i class='ace-icon fa fa-pencil bigger-130'></i></button>";
                $duoifile = end(explode(".", $dl->file_path));
                $dulieu['loaifile'] = $duoifile;
                $dulieu['file_date'] = $dl->datetao;
                $dulieu['file_id'] = $dl->file_id;
                $dulieu['kichthuoc'] = $this->getSize(_LINK_UPLOAD_FILE.$dl->file_path) . " KB";
                $dulieu['ftype_id'] = $dl->ftype_id;
            }
            echo json_encode($dulieu);
        } else {
            echo '0';
        }
    }

    function renamefile() {
        $idfile = $this->input->post("idfile");
        $tenfile = $this->input->post("tenfile");
        $type_file = $this->input->post("file_type");
        $data = array(
            'file_id' => $idfile,
            'file_name' => $tenfile,
            'file_status' => '0',
            'file_date' => date('Y-m-d H:i:s'),
            'ftype_id' => $type_file
        );
        $kiemtra = $this->mod_file->rename($data);
        if ($kiemtra == true) {
            echo "1";
        } else {
            echo "0";
        }
    }

//    function ajax_upload() {
//        $tenfile = $this->input->post("namefile");
//        $idtype = $this->input->post("ftype_id");
//        $kiemtra1 = array(
//            'file_name' => $tenfile,
//            'ftype_id' => $idtype,
//            'file_status' => '0'
//        );
//        $kiemtra = $this->mod_file->kiemtraname($kiemtra1);
//        if ($kiemtra == true) {
//            if (isset($_FILES["file"]["name"])) {
//                $config['upload_path'] = './_uploads/files/';
//                $tenfile1 = date('YmdHis', time());
//                $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|xlsx';
//                $config['file_name'] = $tenfile1;
//                $this->load->library('upload', $config);
//                if (!$this->upload->do_upload('file')) {
//                    echo $this->upload->display_errors();
//                } else {
//                    $data = $this->upload->data();
//                    //thành công
//                    echo "1";
//                    $duoifile = end(explode(".", $data["file_name"]));
//                    $data1 = array(
//                        'file_name' => $tenfile,
//                        'ftype_id' => $idtype,
//                        'file_status' => '0',
//                        'file_path' => './_uploads/files/' . $tenfile1 . '.' . $duoifile,
//                        'file_date' => date('Y-m-d H:i:s'),
//                    );
//                    $file_name = $this->mod_file->uploadfile($data1);
//                }
//            }
//        }
//    }

    function upload_files() {
        $name_file = $_FILES['userfile']['name'];
        $idtype = $this->input->post("ftype_id");
        $link_forder = $this->mod_file->get_forder($idtype);
        $config['upload_path'] = _UPLOADS_PATH.$link_forder;
        $tenfile1 = date('YmdHis', time());
        $config['file_name'] = $tenfile1;
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('userfile')) {
            $token = $this->input->post('token');
            $file_name = $this->upload->data('file_name');
            $data1 = array(
                'file_name' => $name_file,
                'ftype_id' => $idtype,
                'file_status' => '0',
                'file_path' => $file_name, //'./_uploads/files/'.$file_name
                'file_date' => date('Y-m-d H:i:s'),
                    //  'file_token' => $token
            );
            $file_name = $this->mod_file->uploadfile($data1);
        }
    }
    
    function test(){
        $dulieu = $this->mod_file->test();
        foreach($dulieu as $row){
            echo $row->file_forder_id."||".$row->ftype_id."<br>";
        }
    }
    
    function get_link_file(){
        $file_id = $this->input->post('file_id');
        $this->curl->create($this->_api['general'].'get_file');
        $this->curl->post(array(
            'file_id' => $file_id
        ));
        $file = json_decode($this->curl->execute(), TRUE);
        echo base_url($file['site_url'] . $file['file'][0]['file_path']);
    }

//    function delete_files() {
//        $token = $this->input->post('token');
//        $dulieu=$this->mod_file->get_token(array('file_token'=>$token));
//        if ($dulieu==true) {
//            $file_name = $dulieu[0]->file_path;
//            if(file_exists($file_name)){
//                unlink($file);
//            }
//        }
//        $this->mod_file->xoafiletamthoi(array('file_token'=>$token));
//    };
}

?>