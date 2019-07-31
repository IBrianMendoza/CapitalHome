<?php

class Packagerank_m extends My_Model {
	public $_table_name = 'sw_packagerank';
	public $_order_by = 'rank ASC, idpackagerank DESC';
    public $_primary_key = 'idpackagerank';
    public $_own_columns = array();
    public $_timestamps = TRUE;

    public $form_admin = array();

    public $fields_list = null;
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'currency_code' => array('field'=>'currency_code', 'label'=>__('Currency code', 'sw_win'), 'rules'=>'trim|required'),
            'rank' => array('field'=>'rank', 'label'=>__('Rank', 'sw_win'), 'rules'=>'trim|required|is_numeric'),
            'package_name' => array('field'=>'package_name', 'label'=>__('Package name', 'sw_win'), 'rules'=>'trim|required'),
            'package_days' => array('field'=>'package_days', 'label'=>__('Days', 'sw_win'), 'rules'=>'trim|required|is_numeric'),
            'package_price' => array('field'=>'package_price', 'label'=>__('Price', 'sw_win'), 'rules'=>'trim|required|is_numeric|callback_check_rank')
        );
	}

    /* [START] For dinamic data table */
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields = $this->db->list_fields('sw_packagerank');
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE, $agent_id=NULL)
    {
        $this->db->from($this->_table_name);
        $this->db->where($where);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /* [END] For dinamic data table */
    
    public function get_conversions_table($where = array())
    {
        $this->db->order_by($this->_order_by);
        
        if($where)
            $this->db->where($where); 
        
        $query = $this->db->get($this->_table_name);
        
        $conversions_table = array();
        
        if ($query->num_rows() > 0)
        {
           foreach ($query->result() as $row)
           {
                $conversions_table['code'][$row->currency_code] = $row;
                $conversions_table['symbol'][$row->currency_symbol] = $row;
           }
        }
        
        return $conversions_table;
    }
    
    public function get_form_dropdown($column = 'package_name', $where = array(), $show_empty=TRUE)
    {
        $filter = $this->_primary_filter;

        $this->db->order_by($this->_order_by);
        
        if($where)
            $this->db->where($where); 
        
        $dbdata = $this->db->get($this->_table_name)->result_array();
        
        $results = array();
        foreach($dbdata as $key=>$row){
            if(isset($row[$column]))
            {
                if(!empty($row['currency_symbol']))
                {
                    $results[$row[$this->_primary_key]] = $row[$column].' ('.$row['currency_symbol'].')';
                }
                else
                {
                    $results[$row[$this->_primary_key]] = $row[$column];
                }
            }
            
        }
        return $results;
    }

}