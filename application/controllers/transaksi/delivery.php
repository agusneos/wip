<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaksi/m_delivery','record');
        $this->auth->restrict(); //mencegah user yadelivery belum login untuk medeliveryakses halaman ini
        $this->auth->menu(60);
        $this->load->library('PHPExcel');
    }
    
    function index(){
        $this->load->view('transaksi/v_delivery');
    }
    
    function getItem(){
        echo $this->record->getItem();
    }
    
    function create(){
        if(!isset($_POST))	
            show_404();
        
        $item       = addslashes($_POST['item']); 
        $qty        = addslashes($_POST['qty']);
        $opr        = $this->session->userdata('id');
                           
        echo $this->record->create($item, $qty, $opr);
        
    }
    
    function upload(){
        $cond = $this->record->checkSO();
        if($cond){
            echo json_encode(array('success'=>false,'error'=>'Kondisi Stock Opname'));
        }
        else{
            move_uploaded_file($_FILES["upload_delv"]["tmp_name"],
                    "assets/temp_upload/" . $_FILES["upload_delv"]["name"]);
            ini_set('memory_limit', '-1');
            $inputFileName = 'assets/temp_upload/' . $_FILES["upload_delv"]["name"];
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
                $opr            = $this->session->userdata('id');

                $query = $this->record->upload($kode_saga, $qty, $opr);
                if ($query){
                    $ok++;
                }
                else{
                    $ng++;
                }
            }
            unlink('assets/temp_upload/' . $_FILES["upload_delv"]["name"]);
            echo json_encode(array('success'=>true,
                                    'total'=>'Total Data: '.($baris-1),
                                    'ok'=>'Data OK: '.$ok,
                                    'ng'=>'Data NG: '.$ng));
        }
    }
    
}

/* End of file delivery.php */
/* Location: ./application/controllers/transaksi/delivery.php */
