<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends ADMIN_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('mod_nhansu');
    }
    function index() {
        redirect(site_url().'admin/login');
    }
    function setLang($lang) {
        $this->session->set_userdata('ssLang', $lang);
        return;
    }
    function denied() {
        switch ($this->input->get('w')) {
            case 'read':
                $pri = 'đọc';
                break;
            case 'write':
                $pri = 'ghi';
                break;
            case 'delete':
                $pri = 'xóa';
                break;
            case 'update':
                $pri = 'cập nhật';
                break;
            default :
                $pri = '';
                break;
        }
        $this->parser->assign('pri', $pri);
        $this->parser->parse('denied');
    }
    function warning() {
        switch ($this->input->get('w')) {
            case 'confirm':
                $code = 500;
                $warning = 'Xác nhận mật khẩu không đúng';
                break;
            case 'wrong':
				$cached = 'session_id'.$this->session->userdata('ssAdminId');
				if (file_exists("cache/cached/{$this->_host[0]}/{$cached}.json")) unlink("cache/cached/{$this->_host[0]}/{$cached}.json");
                $this->session->unset_userdata('ssAdminId');
                $this->session->unset_userdata('ssAdminFullname');
                $this->session->unset_userdata('ssAdminDonvi');
                $this->session->unset_userdata('ssAdminChucvu');
                $this->session->unset_userdata('ssAccountId');
                $code = 500;
                $warning = 'Mật khẩu cũ không đúng';
                break;
            default:
				$cached = 'session_id'.$this->session->userdata('ssAdminId');
				if (file_exists("cache/cached/{$this->_host[0]}/{$cached}.json")) unlink("cache/cached/{$this->_host[0]}/{$cached}.json");
                $this->session->unset_userdata('ssAdminId');
                $this->session->unset_userdata('ssAdminFullname');
                $this->session->unset_userdata('ssAdminDonvi');
                $this->session->unset_userdata('ssAdminChucvu');
                $this->session->unset_userdata('ssAccountId');
                $code = 600;
                $warning = 'Đổi mật khẩu thành công';
                break;
        }
        $this->parser->assign('code', $code);
        $this->parser->assign('warning', $warning);
        $this->parser->parse('warning');
    }
}
