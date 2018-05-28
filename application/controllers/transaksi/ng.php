<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ng extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_ng','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(61);
    }
    
    function index(){
        $this->load->view('transaksi/v_ng');
    }
    
    function getProses(){
        echo $this->record->getProses();
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
        
        $process    = addslashes($_POST['process']);
        $item       = addslashes($_POST['item']); 
        $qty_ok     = addslashes($_POST['qty_ok']);
        $reason     = addslashes($_POST['reason']);
        $opr        = $this->session->userdata('id');
                           
        echo $this->record->create($process, $item, $qty_ok, $reason, $opr);
        
    }
    
}

/* End of file ng.php */
/* Location: ./application/controllers/transaksi/ng.php */
