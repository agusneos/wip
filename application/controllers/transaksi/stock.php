<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_stock','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(59);
        $this->load->library('FPDF_Ellipse');
    }
    
    function index(){
        $this->load->view('transaksi/v_stock');
    }
    
    function getProses(){
        echo $this->record->getProses();
    }
    
    function getItem(){
        echo $this->record->getItem();
    }
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $process    = addslashes($_POST['process']);
        $item       = addslashes($_POST['item']); 
        $qty        = addslashes($_POST['qty']);
        $opr        = $this->session->userdata('id');
                           
        echo $this->record->create($process, $item, $qty, $opr);
        
    }
    
}

/* End of file stock.php */
/* Location: ./application/controllers/transaksi/stock.php */
