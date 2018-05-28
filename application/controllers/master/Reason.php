<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reason extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('master/m_reason','record');
        $this->auth->restrict(); //mencegah user yang belum login untuk mengakses halaman ini
        $this->auth->menu(62);
    }
    
    function index(){
        if (isset($_GET['grid'])) {
            echo $this->record->index();   
        }
        else {
            $this->load->view('master/v_reason');  
        }
    } 
    
    function create() {
        if(!isset($_POST))	
            show_404();
        
        $m_reason_txt  = addslashes($_POST['m_reason_txt']);
                           
        echo $this->record->create($m_reason_txt);
    }     
    
    function update($m_reason_id=null) {
        if(!isset($_POST))	
            show_404();
        
        $m_reason_txt     = addslashes($_POST['m_reason_txt']);
        
        echo $this->record->update($m_reason_id, $m_reason_txt);
            
    }
            
    function delete(){
        if(!isset($_POST))	
            show_404();

        $m_reason_id = addslashes($_POST['m_reason_id']);
        
        echo $this->record->delete($m_reason_id);
    }
    
}

/* End of file reason.php */
/* Location: ./application/controllers/master/reason.php */