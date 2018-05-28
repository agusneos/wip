<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_scrap extends CI_Model
{    
    static $table1  = 'm_item';
    static $table2  = 't_proc';
    static $table3  = 'm_process_cat';
    static $table4  = 'g_cond';
    static $table5  = 'm_reason';

    public function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function getProses(){
        
        $query  = $this->db->get(self::$table3);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
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
    
    function getReason(){
        
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function create($process, $item, $qty_ng, $reason, $opr){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==1){
            return json_encode(array('success'=>false,'error'=>'Kondisi Stock Opname'));
        }
        else{
            $query1 = $this->db->insert(self::$table2,array(
                't_proc_item'       => $item,
                't_proc_proc'       => $process,
                't_proc_stat'       => 'NG',
                't_proc_qty'        => -$qty_ng,
                't_proc_user'       => $opr,
                't_proc_source'     => 0,
                't_proc_reason'     => $reason
            ));
            if($query1){
                return json_encode(array('success'=>true));
            }
            else {
                return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
            }
        }
    }
            
}

/* End of file m_scrap.php */
/* Location: ./application/models/transaksi/m_scrap.php */