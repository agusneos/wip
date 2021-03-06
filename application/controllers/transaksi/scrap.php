<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrap extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_scrap','record');
        $this->auth->restrict(); //mencegah user yascrap belum login untuk mescrapakses halaman ini
        $this->auth->menu(63);
    }
    
    function index(){
        $this->load->view('transaksi/v_scrap');
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
        $qty_ng     = addslashes($_POST['qty_ng']);
        //$reason     = addslashes($_POST['reason']);
        $reason     = 2;
        $opr        = $this->session->userdata('id');
                           
        echo $this->record->create($process, $item, $qty_ng, $reason, $opr);
        
    }
    
}

/* End of file scrap.php */
/* Location: ./application/controllers/transaksi/scrap.php */
