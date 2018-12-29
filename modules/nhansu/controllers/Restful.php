<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Restful extends API_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_restful');
    }
	/*
	 * Lay tat ca user theo quyen, tang
	 * Input
	 * * * quyen
	 * * * * * Value:
	 * * * * * * - duyetdenghi
	 * * * * * * - soanthao
	 * * * * * * - xemxetbanthao
	 * * * * * * - pheduyet
	 * * * * * * - banhanh
	 * * * * * * - phanphoi
	 * * * tang //If null: lay het the tat ca cac tang
	*/
	function getUsers_post() {
		$arrayPost = $this->post();
        $members = $this->mod_restful->_getUsers($arrayPost);
		$return = array();
        if ($members) {
			foreach ($members as $mem) {
				$return[$mem['donvi']][] = $mem;
			}
			$this->response(array('err_code' => '100', 'status' => 'success', 'members' => $return));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
	}
	/*
	 * Lay tat ca user theo quyen, tang
	 * Input
	 * * * quyen
	 * * * * * Value:
	 * * * * * * - duyetdenghi
	 * * * * * * - soanthao
	 * * * * * * - xemxetbanthao
	 * * * * * * - pheduyet
	 * * * * * * - banhanh
	 * * * * * * - phanphoi
	 * * * tang //If null: lay het the tat ca cac tang
	*/
	function getQuyens_post() {
		$arrayPost = $this->post();
        $members = $this->mod_restful->_getUsers($arrayPost);
		$return = array();
        if ($members) {
			foreach ($members as $mem) {
				$return[$mem['donvi']][] = $mem;
			}
			$this->response(array('err_code' => '100', 'status' => 'success', 'members' => $return));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
	}
    /*
     * Lay cac nhan vien cung phong
     * Input
     * * * * id
     * Output
     * * * * Danh sach nhan vien (id, ten)
     */
    function getinDonvi_post() {
        $arrayInfo = $this->post();
        $member = $this->mod_restful->_gets($arrayInfo['id']);
        $members = $this->mod_restful->_getinDonvi($member['donvi']);
        if ($members) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'members' => $members));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    
    /*
     * Lay cac nhan vien co quyen duyet (trong cung phong)
     * Input
     * * * * id
     * Output
     * * * * Danh sach nhan vien (id, ten)
     */
    function getDuyetinDonvi_post() {
        $arrayInfo = $this->post();
        $member = $this->mod_restful->_gets($arrayInfo['id']);
        $members = $this->mod_restful->_getDuyetinDonvi($member['donvi']);
        if ($members) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'members' => $members));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    
    /*
     * Lay cac nhan vien co quyen xem xet ban thao (trong cung phong)
     * Input
     * * * * id
     * Output
     * * * * Danh sach nhan vien (id, ten)
     */
    function getXemxetinDonvi_post() {
        $arrayInfo = $this->post();
        $member = $this->mod_restful->_gets($arrayInfo['id']);
        $members = $this->mod_restful->_getXemxetinDonvi($member['donvi']);
        if ($members) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'members' => $members));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    
    /*
     * Lay cac nhan vien co quyen theo dinh nghia
     * Input
     * * * * id
     * * * * quyen: duyetbanthao, banhanh, phanphoi
     * Output
     * * * * Danh sach nhan vien (id, ten)
     */
    function getQuyen_post() {
        $arrayInfo = $this->post();
        $members = $this->mod_restful->_getQuyen($arrayInfo['quyen']);
        if ($members) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'members' => $members));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    
    /*
     * Lay cac cac quyen cua mot nhan vien
     * Input
     * * * * id
     * Output
     * * * * Danh sach quyen (id, ten)
     */
    function getPermission_post() {
        $arrayInfo = $this->post();
        $permission = $this->mod_restful->_getPermission($arrayInfo['id']);
        if ($permission) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'permission' => $permission));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    //Lay tat ca cac phong ban
    function getDonvi_post() {
        $donvi = $this->mod_restful->_getDonvi();
        if ($donvi) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'donvi' => $donvi));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    //Lay tat ca cac phong thi nghiem
    function getPhongthinghiem_post() {
        $phongthinghiem = $this->mod_restful->_getPhongthinghiem();
        if ($phongthinghiem) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'phongthinghiem' => $phongthinghiem));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    //Lay tat ca nhan vien
    function getNhanvien_post() {
        $nhanvien = $this->mod_restful->_getNhanvien();
        if ($nhanvien) {
            $this->response(array('err_code' => '100', 'status' => 'success', 'danhsach' => $nhanvien));
        }
        else $this->response(array('err_code' => '101', 'status' => 'fail'));
    }
    //Lay nhan vien cap tren
    function getBoss_post() {
        //Lay
    }
}
