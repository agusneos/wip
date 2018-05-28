<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_proses extends CI_Model
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
    
    function getProsesBefore(){
        
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
        $this->db->where('m_reason_id > 2');
        $query  = $this->db->get(self::$table5);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function createSingle($process_1, $proc_item, $qty_ok, $qty_ng, $reason, $opr){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==1){
            return json_encode(array('success'=>false,'error'=>'Kondisi Stock Opname'));
        }
        else{
            if($qty_ng>0){
                $query1 = $this->db->insert(self::$table2,array(
                    't_proc_item'       => $proc_item,
                    't_proc_proc'       => $process_1,
                    't_proc_stat'       => 'OK',
                    't_proc_qty'        => $qty_ok,
                    't_proc_user'       => $opr,
                    't_proc_source'     => 0,
                    't_proc_reason'     => 1
                ));
                if($query1){
                    $query2 = $this->db->insert(self::$table2,array(
                        't_proc_item'       => $proc_item,
                        't_proc_proc'       => $process_1,
                        't_proc_stat'       => 'NG',
                        't_proc_qty'        => $qty_ng,
                        't_proc_user'       => $opr,
                        't_proc_source'     => 0,
                        't_proc_reason'     => $reason
                    ));
                    if($query2){
                        return json_encode(array('success'=>true));
                    }
                    else {
                        return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                    }
                }
                else {
                    return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                }
            }
            else{
                $query3 = $this->db->insert(self::$table2,array(
                    't_proc_item'       => $proc_item,
                    't_proc_proc'       => $process_1,
                    't_proc_stat'       => 'OK',
                    't_proc_qty'        => $qty_ok,
                    't_proc_user'       => $opr,
                    't_proc_source'     => 0,
                    't_proc_reason'     => 1
                ));
                if($query3){
                    return json_encode(array('success'=>true));
                }
                else {
                    return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                }
            }
        }
    }
    
    function createMulti($process_1, $process_2, $proc_item, $qty_ok, $qty_ng, $reason, $opr){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==1){
            return json_encode(array('success'=>false,'error'=>'Kondisi Stock Opname'));
        }
        else{
            if($qty_ng>0){ //kondisi NG
                $query1a = $this->db->insert(self::$table2,array(
                    't_proc_item'       => $proc_item,
                    't_proc_proc'       => $process_1,
                    't_proc_stat'       => 'OK',
                    't_proc_qty'        => $qty_ok,
                    't_proc_user'       => $opr,
                    't_proc_source'     => 0,
                    't_proc_reason'     => 1
                ));
                if($query1a){
                    $query1b = $this->db->insert(self::$table2,array(
                        't_proc_item'       => $proc_item,
                        't_proc_proc'       => $process_2,
                        't_proc_stat'       => 'OK',
                        't_proc_qty'        => -($qty_ok+$qty_ng),
                        't_proc_user'       => $opr,
                        't_proc_source'     => 0,
                        't_proc_reason'     => 1
                    ));
                    if($query1b){
                        $query1c = $this->db->insert(self::$table2,array(
                            't_proc_item'       => $proc_item,
                            't_proc_proc'       => $process_1,
                            't_proc_stat'       => 'NG',
                            't_proc_qty'        => $qty_ng,
                            't_proc_user'       => $opr,
                            't_proc_source'     => 0,
                            't_proc_reason'     => $reason
                        ));
                        if($query1c){
                            return json_encode(array('success'=>true));
                        }
                        else {
                            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                        }
                    }
                    else {
                        return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                    }
                }
                else {
                    return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                }
            }
            else{ // Kondisi OK
                $query2a = $this->db->insert(self::$table2,array(
                    't_proc_item'       => $proc_item,
                    't_proc_proc'       => $process_1,
                    't_proc_stat'       => 'OK',
                    't_proc_qty'        => $qty_ok,
                    't_proc_user'       => $opr,
                    't_proc_source'     => 0,
                    't_proc_reason'     => 1
                ));
                if($query2a){
                    $query2b = $this->db->insert(self::$table2,array(
                        't_proc_item'       => $proc_item,
                        't_proc_proc'       => $process_2,
                        't_proc_stat'       => 'OK',
                        't_proc_qty'        => -$qty_ok,
                        't_proc_user'       => $opr,
                        't_proc_source'     => 0,
                        't_proc_reason'     => 1
                    ));
                    if($query2b){
                        return json_encode(array('success'=>true));
                    }
                    else {
                        return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                    }
                }
                else {
                    return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
                }
            }
        }
    }
            
}

/* End of file m_proses.php */
/* Location: ./application/models/transaksi/m_proses.php */