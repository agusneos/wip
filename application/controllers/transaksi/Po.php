<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Po extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/M_po','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(64);
        $this->load->library('PHPExcel');
    }
    
    function index(){
        if (isset($_GET['grid'])) {
            echo $this->record->index();   
        }
        else {
            $this->load->view('transaksi/v_po');  
        }
    } 
    
    function create() {
        if(!isset($_POST))	
            show_404();
        
        $t_po_item  = addslashes($_POST['t_po_item']);
        $t_po_qty  = addslashes($_POST['t_po_qty']);
        $t_po_date  = addslashes($_POST['t_po_date']);
                           
        echo $this->record->create($t_po_item, $t_po_qty, $t_po_date);
    }     
    
    function update($t_po_item=null) {
        if(!isset($_POST))	
            show_404();
        
        $t_po_qty     = addslashes($_POST['t_po_qty']);
        $t_po_date  = addslashes($_POST['t_po_date']);
        
        echo $this->record->update($t_po_item, $t_po_qty, $t_po_date);
            
    }
            
    function delete(){
        if(!isset($_POST))	
            show_404();

        $t_po_item = addslashes($_POST['t_po_item']);
        
        echo $this->record->delete($t_po_item);
    }
    
    function getItem(){
        echo $this->record->getItem();
    }
    
    function upload(){
        move_uploaded_file($_FILES["upload_po"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["upload_po"]["name"]);
        ini_set('memory_limit', '-1');
        $inputFileName = 'assets/temp_upload/' . $_FILES["upload_po"]["name"];
        try {
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        }
        catch(Exception $e) {
            die('Error loading file :' . $e->getMessage());
        }
        $worksheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $baris  = count($worksheet);
        $ok     = 0;
        $ng     = 0;
        
        for ($i = 2; $i < ($baris+1); $i++){            
            $kode_saga      = $worksheet[$i]['A'];
            $qty            = $worksheet[$i]['B'];
            $date            = $worksheet[$i]['C'];
                        
            $query = $this->record->upload($kode_saga, $qty, $date);
            if ($query){
                $ok++;
            }
            else{
                $ng++;
            }
        }
        unlink('assets/temp_upload/' . $_FILES["upload_po"]["name"]);
        echo json_encode(array('success'=>true,
                                'total'=>'Total Data: '.($baris-1),
                                'ok'=>'Data OK: '.$ok,
                                'ng'=>'Data NG: '.$ng));
        
    }
}

/* End of file po.php */
/* Location: ./application/controllers/transaksi/po.php */