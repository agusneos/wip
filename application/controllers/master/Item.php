<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_item','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(10);
        $this->load->library('PHPExcel');
    }
    
    function index(){
        if (isset($_GET['grid'])){
            echo $this->record->index();   
        }
        else {
            $this->load->view('master/v_item');  
        }
    } 
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $m_item_id          = addslashes($_POST['m_item_id']);
        $m_item_name        = addslashes($_POST['m_item_name']); 
        $m_item_ext_id      = addslashes($_POST['m_item_ext_id']);
                           
        echo $this->record->create($m_item_id, $m_item_name, $m_item_ext_id);
        
    }     
    
    function update($m_item_id=null){
        if(!isset($_POST))	
            show_404();

        $m_item_name        = addslashes($_POST['m_item_name']);
        $m_item_ext_id      = addslashes($_POST['m_item_ext_id']);
        
        echo $this->record->update($m_item_id, $m_item_name, $m_item_ext_id);
        
    }
        
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_item_id          = addslashes($_POST['m_item_id']);
        
        echo $this->record->delete($m_item_id);
        
    }
    
    //Fungsi Upload Dipending
    function upload() {
        move_uploaded_file($_FILES["fileb"]["tmp_name"],
                "assets/temp_upload/" . $_FILES["fileb"]["name"]);
        ini_set('memory_limit', '-1');
        $inputFileName = 'assets/temp_upload/' . $_FILES["fileb"]["name"];
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
            $kode_hmp       = $worksheet[$i]['A'];
            $nama           = $worksheet[$i]['B'];
            $kode_saga      = $worksheet[$i]['C'];
                        
            $query = $this->record->upload($kode_hmp, $nama, $kode_saga);
            if ($query){
                $ok++;
            }
            else{
                $ng++;
            }
        }
        unlink('assets/temp_upload/' . $_FILES["fileb"]["name"]);
        echo json_encode(array('success'=>true,
                                'total'=>'Total Data: '.($baris-1),
                                'ok'=>'Data OK: '.$ok,
                                'ng'=>'Data NG: '.$ng));
    }
    
}

/* End of file item.php */
/* Location: ./application/controllers/master/item.php */