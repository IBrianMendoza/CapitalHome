<?php

class Invoice_m extends My_Model {
	public $_table_name = 'sw_invoice';
	public $_order_by = 'sw_invoice.idinvoice DESC';
    public $_primary_key = 'idinvoice';
    public $_own_columns = array('user_id');

    public $form_widget = array();
    
    public $form_admin = array();
    
    public $rules_lang = array();
    
    public $paid_via = array();
    
	public function __construct(){
		parent::__construct();
        
        $this->form_admin = array(
            'invoicenum' => array('field'=>'invoicenum', 'label'=>__('Num', 'sw_win'), 'rules'=>'trim|required'),
            'date_created' => array('field'=>'date_created', 'label'=>__('Date created', 'sw_win'), 'rules'=>'trim|required'),
            'date_paid' => array('field'=>'date_paid', 'label'=>__('Date paid', 'sw_win'), 'rules'=>'trim|min_length[10]'),
            'user_id' => array('field'=>'user_id', 'label'=>__('User', 'sw_win'), 'rules'=>'trim|required'),
            'listing_id' => array('field'=>'listing_id', 'label'=>__('Listing', 'sw_win'), 'rules'=>'trim'),
            'subscription_id' => array('field'=>'subscription_id', 'label'=>__('Listing', 'sw_win'), 'rules'=>'trim'),
            'is_activated' => array('field'=>'is_activated', 'label'=>__('Is activated', 'sw_win'), 'rules'=>'trim'),
            'is_disabled' => array('field'=>'is_disabled', 'label'=>__('Is disabled', 'sw_win'), 'rules'=>'trim'),
            'vat_percentage' => array('field'=>'vat_percentage', 'label'=>__('VAT percentage', 'sw_win'), 'rules'=>'trim|is_numeric'),
            'price' => array('field'=>'price', 'label'=>__('Price', 'sw_win'), 'rules'=>'trim|is_numeric|required'),
            'currency_code' => array('field'=>'currency_code', 'label'=>__('Currency code', 'sw_win'), 'rules'=>'trim|required'),
            'paid_via' => array('field'=>'paid_via', 'label'=>__('Paid via', 'sw_win'), 'rules'=>'trim'),
            'company_details' => array('field'=>'company_details', 'label'=>__('Company details', 'sw_win'), 'rules'=>'trim'),
            'note' => array('field'=>'note', 'label'=>__('Note', 'sw_win'), 'rules'=>'trim')
        );
        
        $this->paid_via = array(''=>__('Not paid', 'sw_win'), 'MANUAL'=>__('Manual', 'sw_win'), 'PAYPAL'=>__('PayPal', 'sw_win'));
        
	}
    
    /* [START] For dinamic data table */
    
    public function total_lang($where = array(), $lang_id=1, $check_permission=FALSE)
    {
        $this->db->from($this->_table_name);
        $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_subscriptions', $this->_table_name.'.subscription_id = sw_subscriptions.idsubscriptions', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        $this->db->where($where);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        $this->db->order_by($this->_order_by);
        
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    public function get_pagination_lang($limit, $offset, $lang_id=1, $check_permission=FALSE, $where = array())
    {
        $this->db->from($this->_table_name);
        $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_subscriptions', $this->_table_name.'.subscription_id = sw_subscriptions.idsubscriptions', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        $this->db->where($where);

        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
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
        
        $this->db->join($this->users_table, $this->_table_name.'.user_id = '.$this->users_table.'.ID', 'left');
        $this->db->join('sw_listing_lang', $this->_table_name.'.listing_id = sw_listing_lang.listing_id', 'left');
        $this->db->join('sw_subscriptions', $this->_table_name.'.subscription_id = sw_subscriptions.idsubscriptions', 'left');
        $this->db->where("( lang_id = $lang_id OR lang_id is NULL )", NULL, false);
        
        if(!sw_user_in_role('administrator') && $check_permission)
        {
            $gen_q = array();
            foreach($this->_own_columns as $col)
            {
                $gen_q[]=$col.' = '.get_current_user_id();
            }

            $this->db->where('('.implode(' OR ', $gen_q).')', NULL);
        }
        
        return parent::get_by($where, $single);
    }
    
    public function get_available_fields()
    {
        // Fetch fields with cache
        if(($fields = $this->cache_temp_load('fields')) === FALSE)
        {
            $fields1 = $this->db->list_fields('sw_listing_lang');
            $fields2 = $this->db->list_fields($this->users_table);
            $fields3 = $this->db->list_fields($this->_table_name);
            $fields = array_merge($fields1, $fields2, $fields3);
            $fields = array_flip($fields);
            $this->cache_temp_save($fields, 'fields');
        }
        
        return $fields;
    }
    
    public function check_deletable($id)
    {
        return sw_user_in_role('administrator') === TRUE;
    }
    
     /* [END] For dinamic data table */

    public function invoice_suffix($invoice_num)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->like('invoicenum', $invoice_num, 'after');
        
        $count = $this->db->count_all_results();
        
        if($count == 0)return $invoice_num;
        
        return $invoice_num.$count;
    }
    
    public function disable_by_listing($listing_id)
    {
        $this->db->set('is_disabled', 1);
        $this->db->where('listing_id', $listing_id);
        $this->db->where('(is_activated = 0 OR is_activated IS NULL)', NULL);
        $this->db->update($this->_table_name);
        
        return true;
    }

}