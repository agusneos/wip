<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class M_po extends CI_Model
{    
    static $table1  = 't_po';
    static $table2  = 'm_item';

    public function __construct() {
        parent::__construct();
        //$this->load->helper('database'); // Digunakan untuk memunculkan data Enum
    }
        
    function index()
    {
        $page   = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows   = isset($_POST['rows']) ? intval($_POST['rows']) : 50;
        $offset = ($page-1)*$rows;      
        $sort   = isset($_POST['sort']) ? strval($_POST['sort']) : 't_po_item';
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
        
        $this->db->join(self::$table2, 't_po_item=m_item_id', 'left');        
        $this->db->where($cond, NULL, FALSE);
        $total  = $this->db->count_all_results(self::$table1);
        
        $this->db->join(self::$table2, 't_po_item=m_item_id', 'left');
        $this->db->where($cond, NULL, FALSE);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $query  = $this->db->get(self::$table1);
                   
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
        
    function create($t_po_item, $t_po_qty, $t_po_date)
    {
        $query = $this->db->insert(self::$table1,array(
            't_po_item'     => $t_po_item,
            't_po_qty'      => $t_po_qty,
            't_po_date'     => $t_po_date
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function update($t_po_item, $t_po_qty) {
        $this->db->where('t_po_item', $t_po_item);
        $query = $this->db->update(self::$table1,array(
            't_po_qty'    => $t_po_qty,
            't_po_date'   => $t_po_date
        ));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
        
    function delete($t_po_item)
    {
        $query = $this->db->delete(self::$table1, array('t_po_item' => $t_po_item));
        if($query){
            return json_encode(array('success'=>true));
        }
        else{
            return json_encode(array('success'=>false,'error'=>$this->db->_error_message()));
        }
    }
    
    function getItem(){
        //$this->db->select('m_item_id, m_item_name');
        $query  = $this->db->get(self::$table2);
                   
        $data = array();
        foreach ( $query->result() as $row ){
            array_push($data, $row); 
        }       
        return json_encode($data);
    }
    
    function upload($kode_saga, $qty, $date){
        $this->db->select('m_item_id');
        $this->db->where('m_item_ext_id',$kode_saga);
        $rec1   = $this->db->get(self::$table2);
        $query1 = $rec1->row();
        if($query1){
            if($query1->m_item_id>0){
                $query2 = $this->db->insert(self::$table1,array(
                    't_po_item'     => $query1->m_item_id,
                    't_po_qty'      => $qty,
                    't_po_date'     => $date
                ));
                if($query2){
                    return TRUE;
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

/* End of file t_po.php */
/* Location: ./application/models/transaksi/t_po.php */