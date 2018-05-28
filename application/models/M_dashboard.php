<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_dashboard extends CI_Model
{
    static $table1 = 't_proc';
    static $table2 = 'view_t_proc_total';
    static $table3 = 'm_item';
    static $table4 = 'm_process_cat';
     
    public function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
    
    function index(){
        $this->db->select('m_item_name, m_process_cat_name, t_proc_stat, Qty');
        $this->db->join(self::$table3, 't_proc_item=m_item_id', 'left')
                 ->join(self::$table4, 't_proc_proc=m_process_cat_id', 'left');
        //$this->db->order_by('t_proc_proc', 'ASC');
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function indexOk(){
        $this->db->select('m_process_cat_name, SUM(Qty) AS Qty');
        $this->db->join(self::$table3, 't_proc_item=m_item_id', 'left')
                 ->join(self::$table4, 't_proc_proc=m_process_cat_id', 'left');
        //$this->db->where('t_proc_stat', 'OK');
        $this->db->group_by('t_proc_proc');
        $this->db->order_by('t_proc_proc ASC');
        $query  = $this->db->get(self::$table2);
                   
        //$data = array();
        foreach ( $query->result() as $row ){
            //array_push($data, $row); 
            $hasil[]= $row;
        }       
        //return json_encode($data);
        return $hasil;
    }
    
    function indexNg(){
        $this->db->select('m_item_name, m_process_cat_name, t_proc_stat, Qty');
        $this->db->join(self::$table3, 't_proc_item=m_item_id', 'left')
                 ->join(self::$table4, 't_proc_proc=m_process_cat_id', 'left');
        $this->db->where('t_proc_stat', 'NG');
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
        
    
}

/* End of file m_dashboard.php */
/* Location: ./application/models/m_dashboard.php */