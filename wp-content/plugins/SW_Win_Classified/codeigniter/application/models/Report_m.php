<?php

class Report_m extends MY_Model {
    
    protected $_table_name = 'sw_report';
    protected $_order_by = 'idreport ASC';
    public $_primary_key = 'idreport';
    public $_own_columns = array('user_id');
    public $_timestamps = TRUE;
    
    public $form_listing = array();
    public $form_admin = array();

    public function __construct(){
        parent::__construct();
            
        $this->form_listing = array(
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'rules'=>'trim|required'),
            'user_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'rules'=>'trim'),
            'name' => array('field'=>'name', 'label'=>__('Full name', 'sw_win'), 'rules'=>'trim|required'),
            'phone' => array('field'=>'phone', 'label'=>__('Phone', 'sw_win'), 'rules'=>'trim'),
            'email' => array('field'=>'email', __('Email', 'sw_win'), 'rules'=>'trim|required'),
            'message' => array('field'=>'message', 'label'=>__('Message', 'sw_win'), 'rules'=>'trim'),
            'allow_contact' => array('field'=>'allow_contact', 'label'=>__('Allow admin to contact me', 'sw_win'), 'rules'=>'trim|required'),
            'date_submit' => array('field'=>'date_submit', 'label'=>__('Submit date', 'sw_win'), 'rules'=>'trim|required')
        );
        
        $this->form_admin = $this->form_listing;
    }
    
    public function check_if_exists($user_id, $listing_id)
    {
        $query = $this->db->get_where($this->_table_name, array('user_id'   => $user_id, 
                                                                'listing_id'=>$listing_id));
        return $query->num_rows();
    }
    
    /* [START] For dinamic data table */
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        $this->db->where($where);
        
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
        $this->db->limit($limit);
        $this->db->offset($offset);
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result();
            
        return array();
    }
    
    public function get_by($where, $single = FALSE, $check_permission=FALSE)
    {
        //remove all values from current
        if(!isset($where['lang_id']))
        {
            $where['lang_id'] = 1;
        }
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
        return parent::get_by($where, $single);
    }
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields1 = $this->db->list_fields('sw_listing_lang');
            $fields2 = $this->db->list_fields($this->_table_name);
            $fields = array_merge($fields1, $fields2);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function check_deletable($id)
    {
        if(sw_user_in_role('administrator')) return true;
            
        return false;
    }
    
     /* [END] For dinamic data table */
    
    /* delete all */
    public function delete_all () {
        $this->db->empty_table($this->_table_name);
    }

}



