<?php

class Api extends CI_Controller
{
    private $data = array();
    private $settings = array();
    
    private $lang_config = array();

    
    public function __construct()
    {
        parent::__construct();
        
        $this->lang_config = config_item('lang_config');
        
        $this->lang->load('mobile');
        $this->load->helper('language');
        $this->load->helper('mobile');
                
        $this->load->model('settings_m');
        $this->settings = $this->settings_m->get_fields();
        
        $method = $this->uri->segment(2);
        
        if($method == 'rss')
        {
            header('Content-Type: application/rss+xml; charset=utf-8');
        }
        else
        {
            header('Content-Type: application/json');
        }
    }
   
	public function index()
	{
		echo 'Hello, API here!';
        exit();
	}
    
    /*
        Example call: index.php/api/json/en?
        Supported uri parameters, for pagination:
        $limit_properties=20
        $offset_properties=0
        
        Supported query parameters:
        options_hide
        v_rectangle_ne=46.3905, 16.8329
        v_rectangle_sw=45.9905, 15.999
        search={"search_option_smart":"yellow","v_search_option_2":"Apartment"}
        
        Complete example:
        index.php/api/json/en/20/0?options_hide&search={"search_option_smart":"cestica"}&v_rectangle_ne=46.3905, 16.8329&v_rectangle_sw=45.9905, 15.999
        Example for "from":
        {"v_search_option_36_from":"60000"}
        Example for indeed value:
        {"v_search_option_4":"Sale and Rent"}
        Example for featured:
        {"v_search_option_is_featured":"trueIs Featured"}
    */
    public function json($lang_code=null, $limit_properties=20, $offset_properties=0)
    {
        if($lang_code == NULL)
            exit('Wrong API call!');
            
        if(!isset($this->lang_config[$lang_code]))
            exit('Lang code not configured properly');
            
        $search = $this->input->get_post('search');
        if(empty($search))$search=false; // for old api version
        
        $this->data['message'] = lang_wp('No message returned!');
        $this->data['parameters'] = $search;
        $options_hide = $this->input->get_post('options_hide');

        $this->load->model('field_m');
        $this->load->model('listing_m');
        $this->load->model('file_m');
        $this->load->model('treefield_m');
        $lang_id = $this->lang_config[$lang_code]['id'];
        $lang_name = $this->lang_config[$lang_code]['name'];
        $json_data = array();

        // Prepare search array

        $custom_vars = array('search_is_activated'=>1);

        // smart search
        if(!empty($search))
        {
            $search_array = json_decode($search);
            
            if(empty($search_array) && is_string($search))
            {
                $custom_vars['search_what'] = $search;
            }
        }
        
        // near search
        if(isset($_GET['v_rectangle_sw']) && 
                 $_GET['v_rectangle_ne'])
         {
            $custom_vars['search_rectangle'] = $_GET['v_rectangle_sw'].', '.
                                               $_GET['v_rectangle_ne'];
         }
         
         if(isset($search_array) && is_object($search_array))
         {
            $search_array_r = (array) $search_array;
            
            foreach($search_array_r as $key=>$val)
            {
                if(substr($key, 0, 16) == 'v_search_option_')
                {
                    if(substr($search_array_r[$key], 0, 4) == 'true')$search_array_r[$key] = 'true';
                    
                    $custom_vars['search_'.substr($key, 16)] = $search_array_r[$key];
                }
            }
         }
         
        // category in WP replacing field_2 in script version
        if(isset($custom_vars['search_2']))
        {
            $tree = $this->treefield_m->get_table_tree($lang_id, 1);

            foreach($tree as $key=>$val)
            {
                if($val->value == $custom_vars['search_2'])
                    $custom_vars['search_category'] = $val->idtreefield;
            }

            unset($custom_vars['search_2']);
        }
         
        // order
        
        $order_by = $this->input->get_post('order');
        
        if(!empty($order_by))
        {
            $order_by = str_replace('id', 'idlisting', $order_by);

            if(isset($custom_vars['search_4']))
            if(strpos($custom_vars['search_4'], lang_wp('Rent')) !== FALSE)
            {
                $order_by = str_replace("price", "field_37_int", $order_by);
            }
            
            $order_by = str_replace("price", "field_36_int", $order_by);
            
            $order_by = str_replace("is_featured DESC, ", "", $order_by);
            
            $custom_vars['search_order'] = $order_by;
        }

        prepare_frontend_search_query_GET('listing_m', $custom_vars);
        $this->data['total_results'] = $this->listing_m->total_lang(array(), $lang_id);
        
        prepare_frontend_search_query_GET('listing_m', $custom_vars);
        $estates = $this->listing_m->get_pagination_lang($limit_properties, $offset_properties, $lang_id);
        //$estates = $this->listing_m->get_by($where, false, $limit_properties, $order_by, $offset_properties, FALSE, NULL);
        
        $this->data['sql'] = $this->db->last_query();
        
        $this->data['field_details'] = NULL;

            // customize for old api
            $field_list = $this->field_m->get_field_list($lang_id); //get_lang(NULL, FALSE, $lang_id);
            
            foreach($field_list as $key=>$row)
            {
                $row->option = $row->field_name;
                $row->language_id = $row->lang_id;
                $row->option_id = $row->idfield;
                $row->id = $row->idfield;
                $row->visible = $row->is_preview_visible;
                
                if(empty($row->prefix))$row->prefix='';
                if(empty($row->suffix))$row->suffix='';
                if(empty($row->values))$row->values='';
                
                $this->data['field_details'][] = $row;
            }
            
            // Add custom field
            
            $tree = $this->treefield_m->get_table_tree($lang_id, 1);
            
            $values = '';
            foreach($tree as $key=>$val)
            {
                $values.=$val->value.',';
            }
            if($values != '')$values = substr($values, 0, -1);
            
            $field_2 = new stdClass();
            $field_2->id = "2";
            $field_2->parent_id	= "0";
            $field_2->order	= "12";
            $field_2->type = "DROPDOWN";
            $field_2->visible = "1";
            $field_2->is_required = null;
            $field_2->option_id	= "2";
            $field_2->language_id = "1";
            $field_2->option = "Type";
            $field_2->values = $values;
            $field_2->prefix = "";
            $field_2->suffix = "";
            $field_2->hint = "";
            
            $this->data['field_details'][] = $field_2;
            
            $this->load->model('treefield_m');
            
            foreach($this->data['field_details'] as $row)
            {
//                if($row->type == 'TREE')
//                {
//                    $levels = $this->treefield_m->get_max_level($row->id);
//                    $tree   = $this->treefield_m->get_table_tree($lang_id, $row->id);
//                    
//                    $new_tree = array();
//                    foreach($tree as $row_tree)
//                    {
//                        $new_tree[] = $row_tree;
//                    }
//                    
//                    $this->data['tree_'.$row->id]['levels'] = $levels+1;
//                    $this->data['tree_'.$row->id]['tree']   = $new_tree;
//                }
            }
        
        if(empty($options_hide) || $options_hide == 'true')
            $this->data['field_details']=NULL;
        
        // Set website details
        
        // Add listings to rss feed     
        foreach($estates as $key=>$row){
            $estate_date = array();
            $title = _field($row, 10);
            $url = listing_url($row);
            
            $row->id = $row->idlisting;
            $row->property_id = $row->idlisting;
            $row->json_object = json_decode($row->json_object);
            $row->image_repository = json_decode($row->image_repository);

            
            $category = NULL;
            if(empty($row->field_2))
            {
                $row->field_2 = '-';
                $row->json_object->field_2 = $row->field_2;
                $this->data['category'] = NULL;
                
                if(!empty($row->category_id))
                {
                    $category = $this->treefield_m->get_lang($row->category_id);
                    
                    if(isset($category->{"value_".$lang_id}))
                    {
                        $row->field_2 = $category->{"value_".$lang_id};
                        $row->json_object->field_2 = $row->field_2;
                    }
                        
                }

            }
            
            if(empty($row->field_4))
            {
                $row->field_4 = '-';
                $row->json_object->field_4 = $row->field_4;
            }

            if(isset($row->json_object->field_14) && !empty($row->json_object->field_14) && $row->json_object->field_14 != 'empty')
            {
                $row->json_object->field_6 = $row->json_object->field_14;
            }
            else
            {
                //check for category value
                if(isset($row->field_2) && !empty($row->field_2))
                {
                    $row->json_object->field_6 = sw_generate_slug($row->field_2, 'underscore');
                }
                else
                {
                    // if nothing exists
                    $row->json_object->field_6 = 'empty';
                }
            }
            
            // prepare fields, null are not allowed by old mobile api
            foreach($field_list as $key_f=>$row_f)
            {
                if(!isset($row->json_object->{"field_".$row_f->id}))
                {
                    $row->{"field_".$row_f->id} = "";
                    $row->json_object->{"field_".$row_f->id} = $row->{"field_".$row_f->id};
                }
                
                if($row_f->type == 'CHECKBOX')
                {
                    if(isset($row->json_object->{"field_".$row_f->id}) && 
                             $row->json_object->{"field_".$row_f->id} == '1')
                        $row->json_object->{"field_".$row_f->id} = 'true';
                }
            }
            
            $estate_date['url'] = $url;
            $estate_date['listing'] = $row;
            

            
            // Add first agent/owner data
            
            $this->data['agents'] = $this->listing_m->get_agents($row->idlisting);
            
            if(isset($this->data['agents'][0]))
            {
                $row->name_surname = $this->data['agents'][0]->display_name;
                $row->mail = $this->data['agents'][0]->user_email;
                $row->phone = "-";
                $row->agent_id = $this->data['agents'][0]->ID;
                $row->image_user_filename = "";
            }
            
            $json_data[] = $estate_date;
        }
        
        $this->data['results'] = $json_data;
        
        echo json_encode($this->data);
        exit();
    }    
    
    function abuse ($listing_id = NULL, $lang_code = 'en') {
        $this->data['message'] = '';
        $this->data['success'] = false;
        $error ='';
        
        
        // Check login and fetch user id
        $this->load->helper(array('form', 'url'));
        $this->load->model('listing_m');
        $this->load->model('user_m');
        $this->load->model('report_m');
        
        if($listing_id === NULL) {
            $this->data['message'] = lang_check('Missing listing_id');
            echo json_encode($this->data);
            exit();
        }
        
        /* from GET DATA */
        $_POST['property_id'] = $listing_id;

        $listing= $this->listing_m->get_lang($_POST['property_id'], sw_default_language_id());

        if($listing){
            $_POST['agent_id'] = NULL; //$listing->agent;
            $_POST['listing_id'] = $listing->idlisting;
            $_POST['user_id'] = NULL;
            $_POST['date_submit'] = date('Y-m-d H:i:s');
        }

        $_POST = array_merge($_POST, $_GET);
        
        //Validation
        
        $this->load->library('form_validation');
        
        $rules = $this->report_m->form_listing;
        $this->form_validation->set_rules($rules);
        
        // Process the form
        if($this->form_validation->run() == TRUE)
        {
            $data = $this->report_m->array_from_post(array('property_id', 'agent_id', 'name', 'listing_id', 'user_id',
                                                         'phone', 'email', 'message', 'allow_contact', 'date_submit'));
            
            // Save to database
            $data['date_submit'] = date('Y-m-d H:i:s');
            
            $data_save = array(
                    'user_id' => $_POST['user_id'],
                    'listing_id' => $_POST['listing_id'],
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'message' => $data['message'],
                    'allow_contact' => $data['allow_contact'],
                    'date_submit' => date('Y-m-d H:i:s'),
                );
            $this->report_m->save($data_save);
            
            $admin_email = get_option( 'admin_email' );
            
            // Send mail
            if(!empty($admin_email))
            {
                // send email to admin
                $email_address = get_option('admin_email');
                
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.sw_settings('noreply');
                
                $subject = __('Listing abuse reported', 'sw_win');
                $message = __('Listing reported as abuse from visitor', 'sw_win').': #'.$_POST['listing_id']."<br /><br />";
                
                $message.= '<strong>'.__('Details from post:', 'sw_win')."</strong><br /><br />";
                
                $data_save['link'] = '<a href="'.listing_url($listing).'">'.'#'.$listing->idlisting.', '.$listing->address.'</a><br />';
                
                foreach($data_save as $key=>$val)
                {
                    $message.= ucfirst($key).': '.$val."<br />";
                }
                
                $ret2 = wp_mail( $email_address, $subject, $message, $headers );

                if (!$ret2)
                {
                    $this->data['message'] = lang_check('Email sending failed');
                    $this->data['success'] = false;
                }
            }
            
        }
        else
        {
            $error .= validation_errors();
            
            if(empty($error))$error = lang_check('Validation failed, wrong config?');
        }
        
        $this->data['message'] = $error;
        
        if(empty($this->data['message'])) {
            $this->data['message'] = lang_check('Thanks on your abuse report');
            $this->data['success'] = true;
        }
        
        echo json_encode($this->data);
        exit();
        
    }
    
}
