<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rekap extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('inquiry/m_rekap','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(54);
    }
    
    function index(){
        $this->load->view('inquiry/rekap/v_dialog_rekap'); 
    } 
        
    function getProces(){
        echo $this->record->getProces();
    }
           
    function showRekapBarang(){
        $user   = $this->session->userdata('id');
        if (isset($_GET['grid'])){
            echo $this->record->showRekapBarang($_GET['rekap_proses'], $user, $_GET['rekap_tgl_from'], $_GET['rekap_tgl_to']);
        }
        else{
            $this->load->view('inquiry/rekap/v_rekap_barang');
        }
    }
    
    function delete(){
        if(!isset($_POST))	
            show_404();

        $t_proc_time          = addslashes($_POST['t_proc_time']);
        
        echo $this->record->delete($t_proc_time);
        
    }
        
}

/* End of file rekap.php */
/* Location: ./application/controllers/inquiry/rekap.php */