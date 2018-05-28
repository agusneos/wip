<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mutasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('inquiry/m_mutasi','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(52);
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else {
            $this->load->view('inquiry/v_mutasi');  
        }
    }
    
    function indexOk(){
        if (isset($_GET['grid'])){
            echo $this->record->indexOk();   
        }
        else {
            $this->load->view('inquiry/v_mutasi_ok');  
        }
    }
    
    function indexNg(){
        if (isset($_GET['grid'])){
            echo $this->record->indexNg();   
        }
        else {
            $this->load->view('inquiry/v_mutasi_ng');  
        }
    }
        
}

/* End of file mutasi.php */
/* Location: ./application/controllers/inquiry/mutasi.php */