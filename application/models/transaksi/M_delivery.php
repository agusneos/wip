<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_delivery extends CI_Model
{    
    static $table1  = 'm_item';
    static $table2  = 't_proc';
    static $table3  = 'm_process_cat';
    static $table4  = 'g_cond';
    static $table5  = 'm_reason';
    static $table6  = 't_po';

    public function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
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
    
    function create($item, $qty, $opr){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==1){
            return json_encode(array('success'=>false,'error'=>'Kondisi Stock Opname'));
        }
        else{
            $query1 = $this->db->insert(self::$table2,array(
                't_proc_item'       => $item,
                't_proc_proc'       => 250, //DEFAULT POTONG FINISHED GOOD
                't_proc_stat'       => 'OK',
                't_proc_qty'        => -$qty,
                't_proc_user'       => $opr,
                't_proc_source'     => 0,
                't_proc_reason'     => 1
            ));
            if($query1){
                $query2 = $this->db->insert(self::$table6,array(
                    't_po_item'     => $item,
                    't_po_qty'      => -$qty,
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
    }
    
    function checkSO(){
        $cond = $this->db->get(self::$table4);
        $stat = $cond->row();
        if($stat->g_cond_id==1){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    function upload($kode_saga, $qty, $opr){
        $this->db->select('m_item_id');
        $this->db->where('m_item_ext_id',$kode_saga);
        $rec1   = $this->db->get(self::$table1);
        $query1 = $rec1->row();
        if($query1){
            if($query1->m_item_id>0){
                $query2 = $this->db->insert(self::$table2,array(
                    't_proc_item'       => $query1->m_item_id,
                    't_proc_proc'       => 250, //DEFAULT POTONG FINISHED GOOD
                    't_proc_stat'       => 'OK',
                    't_proc_qty'        => $qty*-1,
                    't_proc_user'       => $opr,
                    't_proc_source'     => 0,
                    't_proc_reason'     => 1
                ));
                if($query2){
                    $query2 = $this->db->insert(self::$table6,array(
                        't_po_item'     => $query1->m_item_id,
                        't_po_qty'      => $qty*-1,
                    ));
                    if($query2){
                        return TRUE;
                    }
                    else {
                        return FALSE;
                    }
                }
                else{
                    return FALSE;
                }
            }
            else{
                return FALSE;
            }
        }
        else{
            return FALSE;
        }
    }
            
}

/* End of file m_delivery.php */
/* Location: ./application/models/transaksi/m_delivery.php */