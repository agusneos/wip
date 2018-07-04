<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_rekap extends CI_Model
{    
    static $table1  = 'm_item';
    static $table2  = 't_proc';
    static $table3  = 'm_process_cat';
    static $table4  = 'a_user';
            
    function __construct() {
        parent::__construct();
      //  $this->load->helper('database'); // Digunakan untuk memunculkan data Enum
        //$this->load->library('subquery');
    }

    function getProces(){
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
       
    function showRekapBarang($proses, $user, $tgl_from, $tgl_to){
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 'm_item_name';
        $order  = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        
        $filterRules = isset($_POST['filterRules']) ? ($_POST['filterRules']) : '';
	$cond = '1=1';
	if (!empty($filterRules)){
            $filterRules = json_decode($filterRules);
            //print_r ($filterRules);
            foreach($filterRules as $rule){
                $rule = get_object_vars($rule);
                $field = $rule['field'];
                $op = $rule['op'];
                $value = $rule['value'];
                if (!empty($value)){
                    if ($op == 'contains'){
                        $cond .= " and ($field like '%$value%')";
                    } else if ($op == 'beginwith'){
                        $cond .= " and ($field like '$value%')";
                    } else if ($op == 'endwith'){
                        $cond .= " and ($field like '%$value')";
                    } else if ($op == 'equal'){
                        $cond .= " and $field = $value";
                    } else if ($op == 'notequal'){
                        $cond .= " and $field != $value";
                    } else if ($op == 'less'){
                        $cond .= " and $field < $value";
                    } else if ($op == 'lessorequal'){
                        $cond .= " and $field <= $value";
                    } else if ($op == 'greater'){
                        $cond .= " and $field > $value";
                    } else if ($op == 'greaterorequal'){
                        $cond .= " and $field >= $value";
                    } 
                }
            }
	}
        
        $this->db->select('t_proc_id, t_proc_item, m_item_name, m_process_cat_name, t_proc_qty, a_user_name, t_proc_time');
        $this->db->join(self::$table1, 't_proc_item=m_item_id', 'left')
                 ->join(self::$table3, 't_proc_proc=m_process_cat_id', 'left')
                 ->join(self::$table4, 't_proc_user=a_user_id', 'left');
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_proc_stat', 'OK')
                 ->where('t_proc_qty > 0')
                 ->where('t_proc_proc', $proses)
                 //->where('t_proc_user', $user)
                 ->where('DATE(t_proc_time) >=', $tgl_from)
                 ->where('DATE(t_proc_time) <=', $tgl_to);
        //$this->db->order_by('t_proc_time', 'ASC');
        $total  = $this->db->count_all_results(self::$table2);
        
        $this->db->select('t_proc_id, t_proc_item, m_item_name, m_process_cat_name, t_proc_qty, a_user_name, t_proc_time');
        $this->db->join(self::$table1, 't_proc_item=m_item_id', 'left')
                 ->join(self::$table3, 't_proc_proc=m_process_cat_id', 'left')
                 ->join(self::$table4, 't_proc_user=a_user_id', 'left');;
        $this->db->where($cond, NULL, FALSE)
                 ->where('t_proc_stat', 'OK')
                 ->where('t_proc_qty > 0')
                 ->where('t_proc_proc', $proses)
                 //->where('t_proc_user', $user)
                 ->where('DATE(t_proc_time) >=', $tgl_from)
                 ->where('DATE(t_proc_time) <=', $tgl_to);
        //$this->db->order_by('t_proc_time', 'ASC');
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row )
        {
            array_push($data, $row); 
        }
 
        $result = array();
	$result['total'] = $total;
	$result['rows'] = $data;
        
        return json_encode($result);
    }
    
    function delete($t_proc_time){
        $query = $this->db->delete(self::$table2, array('t_proc_time' => $t_proc_time));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
}

/* End of file m_rekap.php */
/* Location: ./application/models/inquiry/m_rekap.php */