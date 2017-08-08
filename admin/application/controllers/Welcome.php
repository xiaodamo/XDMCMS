<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }
    
    function index()
    {
        $this->load->view('welcome');
    }

    function getdata($id){
        return ajaxReturn(array());
    }

    function topinfo(){
        $this->load->view('admin/topinfo');
    }

    // --------------------------------------------------------------------

}
