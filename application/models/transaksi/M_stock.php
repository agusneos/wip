<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_stock extends CI_Model
{    
    static $table1  = 'm_item';
    static $table2  = 't_proc';
    static $table3  = 'm_process_cat';
    static $table4  = 'g_cond';

    public function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function getProses(){
        $proc = $this->session->userdata('proc');
        if($proc>0){
            $this->db->where('m_process_cat_id', $proc);
            $query  = $this->db->get(self::$table3);
                   
            $data = array();
            foreach ( $query->result() as $row ){
                array_push($data, $row); 
            } 
            return json_encode($data);
        }
        else{
            $query2  = $this->db->get(self::$table3);
                   
            $data2 = array();
            foreach ( $query2->result() as $row2 ){
                array_push($data2, $row2); 
            }
            return json_encode($data2);
        }        
    }
    
    function getItem(){
        $this->db->select('m_item_id, m_item_name');
        $query  = $this->db->get(self::$table1);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function create($process, $item, $qty, $opr){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==0){
            return json_encode(array('success'=>false,'error'=>'Kondisi Bukan Stock Opname'));
        }
        else{
            $query = $this->db->insert(self::$table2,array(
                't_proc_item'       => $item,
                't_proc_proc'       => $process,
                't_proc_stat'       => 'OK',
                't_proc_qty'        => $qty,
                't_proc_user'       => $opr,
                't_proc_source'     => 1
            ));
            if($query){
                return json_encode(array('success'=>true));
            }
            else {
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            } 
        }
    }
            
}

/* End of file m_stock.php */
/* Location: ./application/models/transaksi/m_stock.php */