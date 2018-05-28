<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proses extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_proses','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(58);
    }
    
    function index(){
        $this->load->view('transaksi/v_proses');
    }
    
    function getProses(){
        echo $this->record->getProses();
    }
    
    function getProsesBefore(){
        echo $this->record->getProsesBefore();
    }
    
    function getItem(){
        echo $this->record->getItem();
    }
    
    function getReason(){
        echo $this->record->getReason();
    }
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $process_1  = addslashes($_POST['process_1']);
        $process_2  = addslashes($_POST['process_2']);
        $proc_item  = addslashes($_POST['proc_item']); 
        $qty_ok     = addslashes($_POST['qty_ok']);
        $qty_ng     = addslashes($_POST['qty_ng']);
        $reason     = addslashes($_POST['reason']);
        $opr        = $this->session->userdata('id');
        
        if($process_2==''){
            echo $this->record->createSingle($process_1, $proc_item, $qty_ok, $qty_ng, $reason, $opr);
        }
        else{
            echo $this->record->createMulti($process_1, $process_2, $proc_item, $qty_ok, $qty_ng, $reason, $opr);
        }        
    }
    
}

/* End of file proses.php */
/* Location: ./application/controllers/transaksi/proses.php */
