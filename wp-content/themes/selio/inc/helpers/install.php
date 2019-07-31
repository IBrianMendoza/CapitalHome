<?php

/*

Custom template install functions.

Custom functionality for functions in wp-content\plugins\SW_Win_Classified\codeigniter\application\models\Install_m.php

*/
function sw_template_install_demolistings(&$this_ci, &$install_log, &$post_data)
{
    // Check if listings table is not empty
    $this_ci->load->model('listing_m');
    $this_ci->load->model('field_m');
    $this_ci->load->model('file_m');
    $this_ci->load->model('repository_m');
    $this_ci->load->model('calendar_m');
    $this_ci->load->model('rates_m');
    $this_ci->load->model('subscriptions_m');
    $this_ci->load->model('treefield_m');
    $this_ci->load->model('settings_m');
    $this_ci->load->library('ghelper');
    $this_ci->load->helpers('text_helper');
    $listings = $this_ci->listing_m->get();

    update_option('date_format', 'F j, Y');
    set_theme_mod('selio_author_section_enabled', '1');
    set_theme_mod('selio_listing_subm_enabled', '1');
    set_theme_mod('selio_login_enabled', '1');
    set_theme_mod('selio_installed', 'true');
    update_option('posts_per_page', 5);
    
    set_theme_mod('selio_phone_setting', '(647) 346-0855');
    set_theme_mod('selio_address_setting', 'CF Fairview Mall, Toronto, ON');
    
    // General pages
    $this_ci->settings_m->save_settings(array('hide_fbcomments_listingpage'=>1)); // Use side map as result page
    $this_ci->settings_m->save_settings(array('enable_multiple_results_page'=>1)); // Use side map as result page
    
    
    /* favicon */
    $favicon = sw_add_wp_image(get_template_directory().'/assets/images/favicon.png');
    update_option('site_icon', $favicon);
    
    if(count($listings) > 0)
    {
        $install_log.= '<div class="alert alert-warning" role="alert">Install demo listings skipped, some already exists</div>';
    }
    else
    {
        // @codingStandardsIgnoreStart
        include(SW_WIN_PLUGIN_PATH.'demo_listings/demo_listings.php');

        if(file_exists(get_template_directory().'/demo_content/demo_listings.php'))
        {
            include(get_template_directory().'/demo_content/demo_listings.php');
        }
        // @codingStandardsIgnoreEnd
        // Fields customization

        foreach($d_fields as $key=>$field)
        {
            $field_id = $field['id'];

            if(empty($field['field_name']))
            {
                // remove field
                $this_ci->db->delete('sw_field_lang', array('field_id' => $field_id));
                $this_ci->db->delete('sw_field', array('idfield' => $field_id));
                continue;
            }

            $data_update = array();

            if(isset($field['parent_id']))
            {
                $data_update['parent_id'] = $field['parent_id'];
            }

            if(isset($field['type']))
            {
                $data_update['type'] = $field['type'];
            }

            if(isset($field['is_hardlocked']))
            {
                $data_update['is_hardlocked'] = $field['is_hardlocked'];
            }

            if(count($data_update) > 0)
                $this_ci->db->update('sw_field', $data_update, array('idfield' => $field_id));

            $data_update = array('field_name'=>$field['field_name']);

            if(isset($field['values']))
            {
                $data_update['values'] = $field['values'];
            }
            
            if(isset($field['suffix']))
            {
                $data_update['suffix'] = $field['suffix'];
            }

            $this_ci->db->update('sw_field_lang', $data_update, array('field_id' => $field_id, 'lang_id'=>1));
        }

        $this_ci->field_m->fields_cache = array();
        
        // Open a known directory, and proceed to read its contents
        $dir = SW_WIN_PLUGIN_PATH.'demo_listings/images/';
        $dir = get_template_directory().'/demo_content/images/listing/';
        
        $files = array();
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if(strpos($file, '.jpg') !== false)
                    $files[] = $file;
                }
                closedir($dh);
            }
        }
        sort($files);
        
        for($i=0; $i<29; $i++)
        {
            $address = $d_address[$i].' '.rand(1,10);

            $gps = $this_ci->ghelper->getCoordinates($address);
            //$gps = array('lat'=>0);
            // If google geo taging return false, then get rand
            if($gps['lat'] == 0)
            {
                $gps['lat'] = rand(3500, 4500) / 100;
                $gps['lng'] = rand(-12000, -8800) / 100;
            }

            $repository_id = $this_ci->repository_m->save(array('model_name'=>'listing_m'));
            
            // Add images into repository
            $file1 = $files[$i];
            $file2 = $files[$i+1];
            $file3 = $files[$i+2];
            $file4 = $files[$i+3];
            $file5 = $files[$i+4];
            $file6 = $files[$i+5];
            $file7 = $files[$i+6];
            $file8 = $files[$i+7];
            $file9 = $files[$i+8];
            
            copy($dir.$file1, sw_win_upload_path().'files/'.$repository_id.$file1);
            copy($dir.$file2, sw_win_upload_path().'files/'.$repository_id.$file2);
            copy($dir.$file3, sw_win_upload_path().'files/'.$repository_id.$file3);
            copy($dir.$file4, sw_win_upload_path().'files/'.$repository_id.$file4);
            copy($dir.$file5, sw_win_upload_path().'files/'.$repository_id.$file5);
            copy($dir.$file6, sw_win_upload_path().'files/'.$repository_id.$file6);
            copy($dir.$file7, sw_win_upload_path().'files/'.$repository_id.$file7);
            copy($dir.$file8, sw_win_upload_path().'files/'.$repository_id.$file8);
            copy($dir.$file9, sw_win_upload_path().'files/'.$repository_id.$file9);
            
            $next_order=0;
            
            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file1,
                'filetype' => 'jpg'
            ));
            
            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file2,
                'filetype' => 'jpg'
            ));
            
            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file3,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file4,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file5,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file6,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file7,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file8,
                'filetype' => 'jpg'
            ));

            $next_order++;
            $file_id = $this_ci->file_m->save(array(
                'repository_id' => $repository_id,
                'order' => $next_order,
                'filename' => $repository_id.$file9,
                'filetype' => 'jpg'
            ));
            
            $this_ci->load->library('UploadHandler', array('initialize'=>FALSE));
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file1, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file2, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file3, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file4, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file5, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file6, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file7, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file8, '');
            $this_ci->uploadhandler->regenerate_versions($repository_id.$file9, '');
            
            $category_id = rand(1, 7);
            if($i<6) {
               $category_id = 1; 
            } elseif($i<13){
               $category_id = 2; 
            } elseif($i<19){
               $category_id = 3; 
            } elseif($i<20){
               $category_id = 4; 
            } elseif($i<21){
               $category_id = 5; 
            } elseif($i<22){
               $category_id = 6; 
            } elseif($i<23){
               $category_id = 7; 
            }
            
            // Define general data
            $data = array('address'=>str_replace(array('Bjelovar, ','Croatia, ','Daruvar, ','Daruvar,','Bjelovar, ','Karlovac, ','Zabok, ','Sesvete, ','Petrinja, '), '',$address),
                          'gps'=>$gps['lat'].', '.$gps['lng'],
                          'is_primary'=>1,
                          'is_featured'=>$i%3==0,
                          'is_activated'=>1,
                          'category_id'=>$category_id,
                          'location_id'=>rand(8, 22),
                          'repository_id'=>$repository_id);
            
            // Define language data
            $desctiption_short = $d_titles[$i].' from '.$d_address[$i].' '.$d_descriptions[rand(0, count($d_descriptions)-1)];
            $desctiption_short = character_limiter($desctiption_short, 85);
            
            // define for widgets
            $input_4_1 = $this_ci->field_m->get_random_value(4, 1);
            if($i>10 && $i<20) {
                $input_4_1 = 'For Sale';
            } elseif($i>20){
                $input_4_1 = 'For Rent';
            }
            
            if($i==24){
                $input_4_1 = 'For Sale';
            }
            
            $data_lang = array('input_10_1'=>$d_titles[$i],
                               'input_8_1'=>$desctiption_short,
                               'input_13_1'=>$d_titles[$i].' from '.$d_address[$i].' '.$d_descriptions[rand(0, count($d_descriptions)-1)],
                               //'input_14_1'=>$this_ci->field_m->get_random_value(14, 1),
                               //'input_2_1'=>$this_ci->field_m->get_random_value(2, 1),
                               'input_4_1'=>$input_4_1,
                               'input_36_1'=>rand(1, 100)*100,
                               'input_57_1'=>rand(1, 40)*50,
                               'input_19_1'=>rand(1, 5),
                               'input_20_1'=>rand(1, 5),
                               'input_5_1'=>rand(5, 30)*10,
                               'input_7_1'=>'Croatia',
                                );
            
            // for checkboxes
            foreach(array(22,23,29,31,32,30,11,27,33) as $j)
            {
                $data_lang['input_'.$j.'_1'] = (string) $this_ci->field_m->get_random_value($j, 1);
            }
            
            /* selio hardcode */
            $data_lang['input_22_1'] = 1;
            $data_lang['input_23_1'] = 1;
            $data_lang['input_29_1'] = 1;
            $data_lang['input_31_1'] = 1;
            $data_lang['input_33_1'] = 1;
            $data_lang['input_11_1'] = 1;
            $data_lang['input_27_1'] = 1;
            $data_lang['input_32_1'] = 1;
            
            // for distances
            for($j=47; $j<51; $j++)
            {
                $data_lang['input_'.$j.'_1'] = rand(1, 900)*10;
            }
            
            $id = $this_ci->listing_m->save_with_lang($data, $data_lang, NULL, 0);

            
            if($category_id == 1){
                $data_calendar = array(
                       'user_id'=>1,
                       'listing_id'=>$id,
                       'calendar_title'=>'Example',
                       'calendar_type'=>'DAY',
                       'is_activated'=>'1',
                       'payment_details'=>'This is simply dummy text of the printing and typesetting industry.',
                    );

                $this_ci->calendar_m->save($data_calendar);

                $data_rate = array(
                       'listing_id'=>$id,
                       'date_from'=>'2019-03-05 15:00:00',
                       'date_to'=>'2022-04-09 15:00:00',
                       'rate_hour'=>'2.50',
                       'rate_night'=>'11.00',
                       'rate_week'=>'70.00',
                       'rate_month'=>'150.00',
                       'currency_code'=>'USD',
                       'min_stay_days'=>'1',
                       'changeover_day'=>'0'
                    );
                $this_ci->rates_m->save($data_rate);
            }

            if($category_id == 3){
                $data_rate = array(
                       'listing_id'=>$id,
                       'date_from'=>'2019-03-05 15:00:00',
                       'date_to'=>'2022-04-09 15:00:00',
                       'rate_hour'=>'2.50',
                       'rate_night'=>'11.00',
                       'rate_week'=>'70.00',
                       'rate_month'=>'150.00',
                       'currency_code'=>'USD',
                       'min_stay_days'=>'1',
                       'changeover_day'=>'0'
                    );
                $this_ci->rates_m->save($data_rate);

                $data_calendar = array(
                       'user_id'=>1,
                       'listing_id'=>$id,
                       'calendar_title'=>'Example',
                       'calendar_type'=>'HOUR',
                       'is_activated'=>'1',
                       'payment_details'=>'This is simply dummy text of the printing and typesetting industry.',
                    );

                $this_ci->calendar_m->save($data_calendar);
            }
            
        }
        
        /* few subscriptions_m examples  */
        $subscriptions = $this_ci->subscriptions_m->get();
        
        /* add only if missing any subscriptions */
        if(selio_plugin_call::sw_count($subscriptions)==0){
        
            $data_subscriptions = array(
                   'currency_code'=>"USD",
                   'subscription_name'=>'Free',
                   'days_limit'=>'7',
                   'listing_limit'=>'1',
                   'subscription_price'=>'0',
                   'is_default'=>'0',
                   'set_activated'=>'1',
                   'user_type'=>'OWNER'
                );
            $this_ci->subscriptions_m->save($data_subscriptions);
            
            $data_subscriptions = array(
                   'currency_code'=>"USD",
                   'subscription_name'=>'Basic',
                   'days_limit'=>'31',
                   'listing_limit'=>'3',
                   'featured_limit'=>'1',
                   'subscription_price'=>'10.99',
                   'is_default'=>'0',
                   'set_activated'=>'0',
                   'user_type'=>'OWNER'
                );
            $this_ci->subscriptions_m->save($data_subscriptions);
            
            $data_subscriptions = array(
                   'currency_code'=>"USD",
                   'subscription_name'=>'Premium',
                   'days_limit'=>'7',
                   'listing_limit'=>'10',
                   'featured_limit'=>'3',
                   'subscription_price'=>'15.99',
                   'is_default'=>'0',
                   'set_activated'=>'0',
                   'user_type'=>'OWNER'
                );
            $this_ci->subscriptions_m->save($data_subscriptions);
        }
        /* end few subscriptions_m examples  */
        
        $reviews = "Nam placerat facilisis placerat. Morbi elit nibh, auctor sit amet sodales id, porttitor eu quam. Aenean dui libero, laoreet quis con sequat vitae, posuere ut sapien. Etiam pharetra nulla vel diam eleifend, eu placerat nunc molestie.";

        /* comments */
        
            $this_ci->load->model('listing_m');
            $this_ci->load->model('review_m');
            $results_obj_id = $this_ci->listing_m->get();
            if($results_obj_id and !empty($results_obj_id))
                foreach ($results_obj_id as $key => $estate_id) {
                    $estate_id = $estate_id->idlisting;
                    $reviews_allpre_listing = $this_ci->review_m->get_by(array('is_visible' => 1,
                                                                            'sw_review.listing_id'=>$estate_id));

                    $user_id_c = rand(2,4);
                    
                    if(!$reviews_allpre_listing || selio_plugin_call::sw_count($reviews_allpre_listing)<2){
                        $data = array();
                        $data['listing_id']= $estate_id;
                        $data['stars']= rand(3, 5);
                        $data['message']=str_replace( "'",'&#039;', $reviews);
                        $data['is_visible']=1;
                        $data['user_id']= $user_id_c;
                        $data['counter_love']=rand(0, 25);
                        $data['counter_like']=rand(0, 25);
                        $data['counter_wow']=rand(0, 25);
                        $data['counter_angry']=rand(0, 25);
                        $id = $this_ci->review_m->save($data);

                        $data = array();
                        $data['listing_id']= $estate_id;
                        $data['stars']= rand(3, 5);
                        $data['message']=str_replace( "'",'&#039;', $reviews);
                        $data['is_visible']=1;
                        $data['user_id']= $user_id_c+1;
                        $data['counter_love']=rand(0, 25);
                        $data['counter_like']=rand(0, 25);
                        $data['counter_wow']=rand(0, 25);
                        $data['counter_angry']=rand(0, 25);
                        $id = $this_ci->review_m->save($data);
                    }
                }
        /* end comments */

        $install_log.= '<div class="alert alert-success" role="alert">Example demo listings added</div>';
    }
    
    
    $install_log.= '<div class="alert alert-success" role="alert">Example agents added</div>';
    
    if(defined('SW_WIN_GEOMAP_PLUGIN_PATH'))
    {
        
        $added_ids = sw_generate_map_demo(2, 'us.svg');
        $images = array_values(array_diff(scandir(get_template_directory().'/assets/img/pic/locations/'), array('.', '..')));
        sort($images);
        $first_id = $added_ids[0];
        $last_id = end($added_ids);
        
        foreach ($images as $key => $value) {
            $images[$key] = sw_add_wp_image(get_template_directory().'/assets/img/pic/locations/'.$value);
        }
        
        for($i=$first_id;$i<=$last_id;$i++)
        {
            if($i==$added_ids[1]) {
                $id_featured_image = $images[1];
            } else if($i==$added_ids[2]) {
                $id_featured_image = $images[2];
            } else if($i==$added_ids[3]) {
                $id_featured_image = $images[3];
            } else if($i==$added_ids[4]) {
                $id_featured_image = $images[4];
            } else {
                $index = ($i-22)%selio_plugin_call::sw_count($images);
                if($index < 0) 
                    $index = abs($index);
                $id_featured_image = $images[$index];
            }
            $data_update = array('featured_image_id'=>$id_featured_image);
            $this_ci->db->update('sw_treefield', $data_update, array('idtreefield' => $i));
        }
    } 
    
}


function sw_add_demo_user(&$this_ci, $username, $name, $surname, $phone=NULL, $password=NULL, $email_address='', $type='AGENT',$position_title='', $listings_related = array(), $address='', $city= '')
{
    
    $user_id = username_exists( $username );
    
    if ( $user_id || (!empty($email_address) && email_exists($email_address)) )
        return;
    
    // Generate the password and create the user
    if(empty($password))
        $password = wp_generate_password( 12, false );

    $user_id = wp_create_user( $username, $password, $email_address );
    
    // Set the nickname
    wp_update_user(
        array(
            'ID'          =>    $user_id,
            'nickname'    =>    $name.' '.$surname,
            'first_name'  =>    $name,
            'last_name'   =>    $surname,
            'description' =>    'Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Duis sed odio sit amet nibh vulputate cursus a sit amet maurs. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odi non mauris vitae erat consequat Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit.',
            'display_name'=>    $name.' '.$surname,
            'user_url'    =>    '//codecanyon.net/user/sanljiljan'  
        )
    );
    
    $available_acc_types = config_item('account_types');
    
    // Set the role
    if(isset($available_acc_types[$type]))
    {
        $user = new WP_User( $user_id );
        $user->set_role($type);
    }
    
    foreach($listings_related as $val)
    {
        $this_ci->db->set(array('listing_id'=>$val,
                                'user_id'=>$user_id));
        $this_ci->db->insert('sw_listing_agent');
    }
    
    $data_ins = array('phone_number'=>$phone, 'position_title'=>$position_title, 'user_id'=>$user_id, 'address'=>$address, 'city'=>$city);
    
    if($user_id==5) {
        $img = sw_add_wp_image(get_template_directory().'/demo_content/images/profile.jpg');
        $data_ins['profile_image'] = $img;
    }
    
    $this_ci->load->model('profile_m');
    $this_ci->profile_m->save($data_ins);

}

function sw_template_install_menu(&$this_ci, &$install_log, &$post_data)
{
    $page_id = selio_plugin_call::sw_settings('register_page', true);
    $page_data = get_page( $page_id );

    $CI = $this_ci;
    
    if(empty($page_data))
    {
        $install_log.= '<div class="alert alert-warning" role="alert">Plugin pages not defined, define it first</div>';
        return $install_log;
    }
    
    // Check if listings table is not empty
    $this_ci->load->model('listing_m');
    $this_ci->load->model('field_m');
    $this_ci->load->model('file_m');
    $this_ci->load->model('repository_m');
    $this_ci->load->model('treefield_m');
    $this_ci->load->library('ghelper');
    $this_ci->load->helpers('text_helper');
    
    // @codingStandardsIgnoreStart
    include(SW_WIN_PLUGIN_PATH.'demo_listings/demo_listings.php');

    if(file_exists(get_template_directory().'/demo_content/demo_listings.php'))
    {
        include(get_template_directory().'/demo_content/demo_listings.php');
    }
    // @codingStandardsIgnoreEnd
    // Fields customization

    
    // update pages

    $my_post = array(
        'ID'           => selio_plugin_call::sw_settings('listing_preview_page', true),
        'page_template' => 'templates/template-listing-preview.php',
    );
    wp_update_post( $my_post );

    $my_post = array(
        'ID'           => selio_plugin_call::sw_settings('results_page', true),
        'post_title'   => esc_html__('Half Map', 'selio'),
        'page_template' => 'templates/template-results-half-map.php',
    );
    wp_update_post( $my_post );

    $my_post = array(
        'ID'           => selio_plugin_call::sw_settings('user_profile_page', true),
        'page_template' => 'templates/template-agent-profile.php',
    );
    wp_update_post( $my_post );
    
    // Change search form for template needs
    $data_ins = array('fields_order'=>'{  "PRIMARY": {  "CATEGORY":{"direction":"NONE", "style":"", "class":"", "id":"NONE", "type":"CATEGORY"} ,"WHERE_SEARCH":{"direction":"NONE", "style":"", "class":"hide_geo", "id":"NONE", "type":"WHERE_SEARCH"} ,"LOCATION":{"direction":"NONE", "style":"", "class":"hide_quick", "id":"NONE", "type":"LOCATION"} }, "SECONDARY": {  "CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} } }');

    if(isset($d_data_ins)) $data_ins = $d_data_ins;

    $this_ci->load->model('searchform_m');
    $this_ci->searchform_m->save($data_ins, 1);
    

    $data_ins_items = array('fields_order'=>'{  "PRIMARY": {  "CHECKBOX_22":{"direction":"NONE", "style":"", "class":"", "id":"22", "type":"CHECKBOX"} ,"CHECKBOX_29":{"direction":"NONE", "style":"", "class":"", "id":"29", "type":"CHECKBOX"} ,"CHECKBOX_32":{"direction":"NONE", "style":"", "class":"", "id":"32", "type":"CHECKBOX"} ,"CHECKBOX_11":{"direction":"NONE", "style":"", "class":"", "id":"11", "type":"CHECKBOX"} ,"CHECKBOX_30":{"direction":"NONE", "style":"", "class":"", "id":"30", "type":"CHECKBOX"} ,"CHECKBOX_23":{"direction":"NONE", "style":"", "class":"", "id":"23", "type":"CHECKBOX"} }, "SECONDARY": { } }');
    if(isset($d_data_ins_items)) $data_ins_items = $d_data_ins_items;
     
    $this_ci->load->model('searchform_m');
    $this_ci->searchform_m->save($data_ins_items, 2);
    
    
    
        // Change categories for template

        // @codingStandardsIgnoreStart
        if(file_exists(get_template_directory().'/demo_content/demo_listings.php'))
        {
            include(get_template_directory().'/demo_content/demo_listings.php');
        }
        // @codingStandardsIgnoreEnd
        $categories = array(
            array('title'=>'Restaurant', 'marker_icon_filename'=>'restaurants.png', 'featured_image_filename'=>'pizza.jpg'), 
            array('title'=>'Shop', 'marker_icon_filename'=>'shop.png', 'featured_image_filename'=>'bice.jpg'), 
            array('title'=>'Bakery', 'marker_icon_filename'=>'bakery.png', 'featured_image_filename'=>'novikov.jpg'),  
            array('title'=>'Coffee', 'marker_icon_filename'=>'coffe.png', 'featured_image_filename'=>'coffe.jpg'), 
            array('title'=>'Nightlife', 'marker_icon_filename'=>'nightlife.png', 'featured_image_filename'=>'bar-a.jpg'),  
            array('title'=>'Hotel', 'marker_icon_filename'=>'hotel.png', 'featured_image_filename'=>'scene.jpg'),  
            array('title'=>'Library', 'marker_icon_filename'=>'library.png', 'featured_image_filename'=>'trend.jpg')
        );

        $categories = array();

        if(isset($d_categories)) $categories = $d_categories;
        
        $icons = array('icomoon-restaurant','icomoon-shopping','icomoon-bakery','icomoon-coffe','icomoon-martini','icomoon-hotel','fa fa-street-view');
        foreach($categories as $key=>$category)
        {
            $id_pin_icon = $id_image = NULL;
            $level = $parent_id = 0;

            $row_exists = false; 
            if($this_ci->treefield_m->get($key+1))
                $row_exists = true; 
            
            if(isset($category['featured_image_filename']))
            {
                $id_image = sw_add_wp_image(get_template_directory().'/assets/img/pic/listings/'.$category['featured_image_filename']);
            }

            if(isset($category['level']))$level = $category['level'];
            if(isset($category['parent_id']))$parent_id = $category['parent_id'];
            
            $icon_font = '';
            if(isset($icons[$key]))
                $icon_font = $icons[$key];

            if(isset($category['font_icon_code']))$icon_font = $category['font_icon_code'];

            $data_update = array('field_id'=>1, 'marker_icon_id'=>$id_pin_icon, 'level'=>$level, 'parent_id'=>$parent_id, 'featured_image_id'=>$id_image, 'font_icon_code'=>$icon_font);
            if($row_exists){
                $this_ci->db->update('sw_treefield', $data_update, array('idtreefield' => $key+1));
            } else {
                $data = array_merge(array(
                    'field_id'=>1, 
                    'parent_id'=>0, 
                    'order'=>0, 
                    'level'=>'0', 
                    'marker_icon_id'=>NULL, 
                    'featured_image_id'=>NULL, 
                    'font_icon_code'=>NULL, 
                ), $data_update);
                $data['field_id'] = 1;
                $this_ci->db->insert('sw_treefield', $data);
                $id = $this_ci->db->insert_id();
                
            }
            
            $data_update = array('value'=>$category['title']);
            if($row_exists){
                $this_ci->db->update('sw_treefield_lang', $data_update, array('treefield_id' => $key+1, 'lang_id'=>1));
            } else {
                $data = array_merge(array(
                    'treefield_id'=>$id, 
                    'lang_id'=>1, 
                    'value'=>'Title', 
                ), $data_update);
                
                $this_ci->db->insert('sw_treefield_lang', $data);
            }
            
            if(isset($category['hidden_fields_list']))
            {
                $data_update_dep = array(
                        'hidden_fields_list'=>$category['hidden_fields_list']
                );

                $this_ci->db->update('sw_dependentfields', $data_update_dep, array('treefield_id' => $key+1));
            }
        }

    // Remove all field dependency
    $data_update = array('hidden_fields_list'=>'');
    $this_ci->db->update('sw_dependentfields', $data_update, array('field_id'=>1));

    // insert demo posts because of use in menu
    /* add categoris */ 
    $categories_id = array();
    if(!is_category('Models'))
        $categories_id[] = wp_create_category('Models', 0);
    if(!is_category('Fashion'))
        $categories_id[] = wp_create_category('Fashion', 0);
    if(!is_category('Lifestyle'))
        $categories_id[] = wp_create_category('Lifestyle', 0);
    if(!is_category('Business'))
        $categories_id[] = wp_create_category('Business', 0);
    if(!is_category('Travel'))
        $categories_id[] = wp_create_category('Travel', 0);
    
    // Add widgets example
    
    $titles = array(
        'The Truth About Places',
        'Memory Vault for pictures',
        'How Much Do You Know about news',
        'Beaches in Croatia',
        'Ten Things You Did Know About',
        'Apartment on nice Island',
        'Five Facts You Never Knew',
        'Places in Zagreb',
        'Croatia Holidays market',
        'This Province Says',
        'To Get Your Head Out Of The Sand',
        'Strategies For Beginners',
        'The Secrets To Croatia',
        'So Simple Even Your Kids Can Do It',
        
    );
    //delete Helo world Post
    wp_delete_post(1);
    
    $last_post_inserted_id = NULL;
    $multipurpose = '';
    $post_image = sw_add_wp_image(get_template_directory().'/assets/img/pic/post-image.jpg');
    // Insert 11 demo posts
    $id_imgs = array();
    for($i=11;$i>=1;$i--)
    {
        $id_imgs[$i] = sw_add_wp_image(get_template_directory().'/demo_content/'.$multipurpose.'images/news-'.$i.'.jpg');
    }
    
    for($i=11;$i>=1;$i--)
    {
        if($i<3)
            $post_tags = array('Real Estate','Business','Construction');
        elseif($i<6)
            $post_tags = array('Construction','Apartment','Houzez');
        elseif($i<8)
            $post_tags = array('Location','Alignment','Top Views');
        elseif($i<10)
            $post_tags = array('Blog','Business Development');
        else
            $post_tags = array('Sensation','Top views');
            
        $last_post_inserted_id = sw_insert_demo_post($titles[$i], NULL, $id_imgs[$i], $id_imgs, $multipurpose, $categories_id,$post_image,$post_tags);
    }


    // Import elementor templates
    sw_elementor_import_templates(get_template_directory() . '/demo_content/elementor/');

    // General pages
    $post_insert = sw_create_page(esc_html__('Compare listings', 'selio'));
    $this_ci->load->model('settings_m');
    $this_ci->settings_m->save_settings(array('compare_page'=>$post_insert->ID)); // Use side map as result page
    
    $post_insert = sw_create_page(esc_html__('Login page', 'selio'),'','templates/template-full-width-clear.php');
    $this_ci->load->model('settings_m');
    $this_ci->settings_m->save_settings(array('register_page'=>$post_insert->ID)); // Use side map as result page
    
    wp_delete_nav_menu('Primary menu');
    
    $menus = get_registered_nav_menus();
    
    // first menu defined by template
    $first_menu = key($menus);
    
    if ( has_nav_menu($first_menu) ) {
         $install_log.= '<div class="alert alert-warning" role="alert">Assigned menu already exists, add pages manually</div>';
    }
    else
    {
        // create menu and assign to first
        
        // Check if the menu exists
        $menu_name = 'Primary menu';
        $menu_exists = wp_get_nav_menu_object( $menu_name );
        $menu_term = $menu_exists;
        
        // If it doesn't exist, let's create it.
        if( !$menu_exists ){
            
            $menu_id = wp_create_nav_menu($menu_name);

            $this_ci->load->model('settings_m');
            $this_ci->settings_m->save_settings(array('enable_multiple_results_page'=>1, 'show_locations'=>1)); // Use more result pages for presentation

            // Create demo pages
            $demo_pages = array();
            
            if(sw_get_menu_item_by_title($menu_id, esc_html__('Home', 'selio')) == NULL)
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  esc_html__('Home', 'selio'),
                    'menu-item-classes' => 'home',
                    'menu-item-url' => home_url( '/' ), 
                    'menu-item-status' => 'publish'));

            if(sw_get_menu_item_by_title($menu_id, esc_html__('White Menu with Center Search', 'selio')) == NULL)
            {
                $demo_pages[0] = sw_create_page(esc_html__('White Menu with Center Search', 'selio'), '', 'elementor_canvas');
                $demo_pages[1] = sw_create_page(esc_html__('Image Menu with Center Search', 'selio'), '', 'elementor_canvas');
                $demo_pages[2] = sw_create_page(esc_html__('Map with Center Search', 'selio'),'', 'elementor_canvas');
                $demo_pages[3] = sw_create_page(esc_html__('Geo SVG Map', 'selio'), '', 'elementor_canvas');
                $demo_pages[4] = sw_create_page(esc_html__('Slider Header', 'selio'), '', 'elementor_canvas');
                $demo_pages[5] = sw_create_page(esc_html__('Image Header with Description', 'selio'), '', 'elementor_canvas');
                $demo_pages[6] = sw_create_page(esc_html__('More Search Features', 'selio'), '', 'elementor_canvas');

                sw_elementor_assign($demo_pages[0]->ID, get_template_directory() . '/demo_content/elementor/white-menu-with-center-search.json');
                sw_elementor_assign($demo_pages[1]->ID, get_template_directory() . '/demo_content/elementor/image-menu-with-center-search.json');
                sw_elementor_assign($demo_pages[2]->ID, get_template_directory() . '/demo_content/elementor/map-with-center-search.json');
                sw_elementor_assign($demo_pages[3]->ID, get_template_directory() . '/demo_content/elementor/geo-svg-map.json');
                sw_elementor_assign($demo_pages[4]->ID, get_template_directory() . '/demo_content/elementor/slider-header.json');
                sw_elementor_assign($demo_pages[5]->ID, get_template_directory() . '/demo_content/elementor/image-header-with-description.json');
                sw_elementor_assign($demo_pages[6]->ID, get_template_directory() . '/demo_content/elementor/more-search-features.json');

                // Define homepage
                update_option( 'show_on_front', 'page' );
                update_option( 'page_on_front', $demo_pages[0]->ID );
                
                $parent_menu_item = sw_get_menu_item_by_title($menu_id, esc_html__('Home', 'selio'));
                
                if(is_object($parent_menu_item))
                {
                    $parent_id = $parent_menu_item->ID;
                    
                    for($i_p=0;$i_p<=5;$i_p++)
                    {
                        if(!isset($demo_pages[$i_p])) continue;

                        $page_id = $demo_pages[$i_p]->ID;
                        $page_data = get_page( $page_id );
                        
                        if(!empty($page_data))
                        wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                                   'menu-item-object' => 'page',
                                                                   'menu-item-object-id' => $page_id,
                                                                   'menu-item-parent-id' => $parent_id,
                                                                   'menu-item-type' => 'post_type',
                                                                   'menu-item-status' => 'publish'));
                    }
                }
            }
            
            if(sw_get_menu_item_by_title($menu_id, esc_html__('Features', 'selio')) == NULL)
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  esc_html__('Features', 'selio'),
                    'menu-item-classes' => 'find',
                    'menu-item-url' => '#', 
                    'menu-item-status' => 'publish'));

            if(sw_get_menu_item_by_title($menu_id, esc_html__('Features Example', 'selio')) == NULL)
            {
                $demo_pages[6] = sw_create_page(esc_html__('Features Example', 'selio'), '', 'elementor_canvas');
                $demo_pages[7] = selio_plugin_call::sw_settings('results_page');

                sw_elementor_assign($demo_pages[6]->ID, get_template_directory() . '/demo_content/elementor/features-example.json');

                // Change categories for template
                $categories = array(
                    array('title'=>'Restaurant', 'marker_icon_filename'=>'restaurants.png', 'featured_image_filename'=>'pizza.jpg'), 
                    array('title'=>'Shop', 'marker_icon_filename'=>'shop.png', 'featured_image_filename'=>'bice.jpg'), 
                    array('title'=>'Bakery', 'marker_icon_filename'=>'bakery.png', 'featured_image_filename'=>'novikov.jpg'),  
                    array('title'=>'Coffee', 'marker_icon_filename'=>'coffe.png', 'featured_image_filename'=>'coffe.jpg'), 
                    array('title'=>'Nightlife', 'marker_icon_filename'=>'nightlife.png', 'featured_image_filename'=>'bar-a.jpg'),  
                    array('title'=>'Hotel', 'marker_icon_filename'=>'hotel.png', 'featured_image_filename'=>'scene.jpg'),  
                    array('title'=>'Library', 'marker_icon_filename'=>'library.png', 'featured_image_filename'=>'trend.jpg')
                );

                $multipurpose = '';
                if(file_exists(get_template_directory().'/demo_content/demo_listings.php'))
                {
                    // @codingStandardsIgnoreStart
                    include(get_template_directory().'/demo_content/demo_listings.php');
                    // @codingStandardsIgnoreEnd
                }

                $categories = array();
                if(isset($d_categories)) $categories = $d_categories;
                $demo_pages[8] = sw_create_page($categories[0]['title'], '', 'templates/template-results-half-map.php');
                $demo_pages[9] = sw_create_page($categories[1]['title'], '', 'templates/template-results-half-map.php');
                $demo_pages[10] = sw_create_page($categories[2]['title'], '', 'templates/template-results-half-map.php');

                $parent_menu_item = sw_get_menu_item_by_title($menu_id, esc_html__('Features', 'selio'));
                if(is_object($parent_menu_item))
                {
                    $parent_id = $parent_menu_item->ID;
                    
                    for($i_p=6;$i_p<=10;$i_p++)
                    {
                        if(!isset($demo_pages[$i_p])) continue;

                        if(is_numeric($demo_pages[$i_p]))
                            $page_id = $demo_pages[$i_p];
                        else
                            $page_id = $demo_pages[$i_p]->ID;

                        $page_data = get_page( $page_id );
                        
                        $custom_page_url='';
                        $menu_item_object_id = $page_id;
                        $menu_item_object = 'page';
                        $menu_item_type = 'post_type';


                        if($i_p==-1) // Disabled
                        {
                            $custom_uri='?';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = esc_url(get_permalink($page_id)).$custom_uri.'search_what=zagreb';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        
                        if(!empty($page_data))
                        wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                                   'menu-item-object' => $menu_item_object,
                                                                   'menu-item-object-id' => $menu_item_object_id,
                                                                   'menu-item-parent-id' => $parent_id,
                                                                   'menu-item-type' => $menu_item_type,
                                                                   'menu-item-url' => $custom_page_url,
                                                                   'menu-item-status' => 'publish'));
                    }
                }
            }
            
            if(sw_get_menu_item_by_title($menu_id, esc_html__('Listing', 'selio')) == NULL)
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  esc_html__('Listing', 'selio'),
                    'menu-item-classes' => 'about',
                    'menu-item-url' => '#', 
                    'menu-item-status' => 'publish'));

            if(sw_get_menu_item_by_title($menu_id, esc_html__('List Layout with Map', 'selio')) == NULL)
            {
                $demo_pages[20] = sw_create_page(esc_html__('List Layout with Map', 'selio'), '', 'elementor_canvas');
                $demo_pages[21] = sw_create_page(esc_html__('List Layout with Sidebar', 'selio'),  '', 'elementor_canvas');
                
                $demo_pages[22] = selio_plugin_call::sw_settings('user_profile_page');
                $demo_pages[23] = selio_plugin_call::sw_settings('listing_preview_page');

                sw_elementor_assign($demo_pages[20]->ID, get_template_directory() . '/demo_content/elementor/list-layout-with-map.json');
                sw_elementor_assign($demo_pages[21]->ID, get_template_directory() . '/demo_content/elementor/list-layout-with-sidebar.json');
                
                $parent_menu_item = sw_get_menu_item_by_title($menu_id, esc_html__('Listing', 'selio'));
                if(is_object($parent_menu_item))
                {
                    $parent_id = $parent_menu_item->ID;
                    
                    for($i_p=20;$i_p<=29;$i_p++)
                    {
                        if(!isset($demo_pages[$i_p])) continue;

                        if(is_numeric($demo_pages[$i_p]))
                            $page_id = $demo_pages[$i_p];
                        else
                            $page_id = $demo_pages[$i_p]->ID;
                        
                        $page_data = get_page( $page_id );                         
                                                                   
                        $custom_page_url='';
                        $menu_item_object_id = $page_id;
                        $menu_item_object = 'page';
                        $menu_item_type = 'post_type';
                        
                        if($i_p==22)
                        {
                            $custom_uri='';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = esc_url(get_permalink($page_id)).$custom_uri.'5';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        elseif($i_p==23)
                        {
                            $custom_uri='';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = esc_url(get_permalink($page_id)).$custom_uri.'5';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        
                        if(!empty($page_data))
                        wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                                   'menu-item-object' => $menu_item_object,
                                                                   'menu-item-object-id' => $menu_item_object_id,
                                                                   'menu-item-parent-id' => $parent_id,
                                                                   'menu-item-type' => $menu_item_type,
                                                                   'menu-item-url' => $custom_page_url,
                                                                   'menu-item-status' => 'publish'));
                                                                   
                    }
                }
            }

            if(sw_get_menu_item_by_title($menu_id, esc_html__('Pages', 'selio')) == NULL)
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' =>  esc_html__('Pages', 'selio'),
                    'menu-item-classes' => 'pages',
                    'menu-item-url' => '#', 
                    'menu-item-status' => 'publish'));

            if(sw_get_menu_item_by_title($menu_id, esc_html__('Blog Grid', 'selio')) == NULL)
            {
                //$demo_pages_about = sw_create_page(esc_html__('Pages', 'selio'), '', '');

                $demo_pages[30] = sw_create_page(esc_html__('Blog Grid', 'selio'), '', 'elementor_canvas');
                $demo_pages[31] = sw_create_page(esc_html__('Blog Standard', 'selio'), '', 'templates/template-blog-standard.php');
                $demo_pages[32] = sw_create_page(esc_html__('Blog Open', 'selio'), '', '');
                $demo_pages[33] = sw_create_page(esc_html__('About', 'selio'), '', 'elementor_canvas');
                $demo_pages[34] = sw_create_page(esc_html__('Contact', 'selio'), '', 'elementor_canvas');
                $demo_pages[35] = sw_create_page(esc_html__('404', 'selio'), '', '');
                
                sw_elementor_assign($demo_pages[30]->ID, get_template_directory() . '/demo_content/elementor/blog-grid.json');
                sw_elementor_assign($demo_pages[33]->ID, get_template_directory() . '/demo_content/elementor/about.json');
                sw_elementor_assign($demo_pages[34]->ID, get_template_directory() . '/demo_content/elementor/contact.json');

                update_option( 'page_for_posts', $demo_pages[31]->ID );

                // update pages
                
                $parent_menu_item = sw_get_menu_item_by_title($menu_id, esc_html__('Pages', 'selio'));
                if(is_object($parent_menu_item))
                {
                    $parent_id = $parent_menu_item->ID;
                    
                    for($i_p=30;$i_p<=39;$i_p++)
                    {
                        if(!isset($demo_pages[$i_p])) continue;

                        if(is_numeric($demo_pages[$i_p]))
                            $page_id = $demo_pages[$i_p];
                        else
                            $page_id = $demo_pages[$i_p]->ID;
                        
                        $page_data = get_page( $page_id );                         
                                                                
                        $custom_page_url='';
                        $menu_item_object_id = $page_id;
                        $menu_item_object = 'page';
                        $menu_item_type = 'post_type';
                        
                        if($i_p==10)
                        {
                            $custom_uri='';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = esc_url(get_permalink($page_id)).$custom_uri.'5';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        elseif($i_p==12)
                        {
                            $custom_uri='';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = esc_url(get_permalink($page_id)).$custom_uri.'5';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        elseif($i_p==32)
                        {
                            $custom_page_url        = '';
                            $menu_item_object       = 'post';
                            $menu_item_object_id    = $last_post_inserted_id;
                            $menu_item_type         = 'post_type';
                        }
                        elseif($i_p==35)
                        {
                            $custom_uri='';
                            if(substr_count(get_permalink($page_id), '?') > 0)
                                $custom_uri = '&';
                            $custom_page_url        = get_permalink($page_id).$custom_uri.'404-example';
                            $menu_item_object       = '';
                            $menu_item_object_id    = 0;
                            $menu_item_type         = 'custom';
                        }
                        
                        if(!empty($page_data))
                        wp_update_nav_menu_item($menu_id, 0, array('menu-item-title' => $page_data->post_title,
                                                                'menu-item-object' => $menu_item_object,
                                                                'menu-item-object-id' => $menu_item_object_id,
                                                                'menu-item-parent-id' => $parent_id,
                                                                'menu-item-type' => $menu_item_type,
                                                                'menu-item-url' => $custom_page_url,
                                                                'menu-item-status' => 'publish'));
                                                                
                    }
                }
            }

            // assign menu to top menu
            $locations = get_theme_mod( 'nav_menu_locations' );
            $locations[$first_menu] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
            
            $install_log.= '<div class="alert alert-success" role="alert">Menu added and assigned to first</div>';
        }
        else
        {
            // assign menu to top menu
            $locations = get_theme_mod( 'nav_menu_locations' );
            $locations[$first_menu] = $menu_term;
            set_theme_mod('nav_menu_locations', $locations);
            
            $install_log.= '<div class="alert alert-success" role="alert">Menu "Primary menu" already exists and assigned</div>';
        }



        // Add widgets example

        sw_insert_widget('footer-1', 'widget-logo-social', $this_ci, $install_log, $post_data, array('title'=>'', 'css_class'=>'col-xl-3 col-sm-6 col-md-4'), TRUE);
        sw_insert_widget('footer-1', 'widget-footer-contacts', $this_ci, $install_log, $post_data, array('title'=>'Contact Us','address'=>'432 Park Ave, New York, NY 10022','phone'=>'(844) 380-8603','mail'=>'support@selio.com','custom_link'=>'#','custom_link_title'=>'Contact Us', 'css_class'=>'col-xl-3 col-sm-6 col-md-3'), TRUE);
        sw_insert_widget('footer-1', 'widget-follow-us', $this_ci, $install_log, $post_data, array('title'=>'Follow Us', 'css_class'=>'col-xl-6 col-sm-12 col-md-5'), TRUE);
        
        sw_insert_widget('sidebar-listing-1', 'sw_win_listingagent_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-listing-1', 'sw_win_contactform_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-results-1', 'categories', $this_ci, $install_log, $post_data, array('count'=>'1'), TRUE);
        sw_insert_widget('sidebar-listing-1', 'sw_win_latestlisting_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-listing-1', 'sw_win_mortgage_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);

        sw_insert_widget('sidebar-profile-1', 'sw_win_contactform_widget', $this_ci, $install_log, $post_data, array('title'=>'Contact Agent'), TRUE);
    
        sw_insert_widget('sidebar-results-1', 'sw_win_primarysearch_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-results-1', 'sw_win_secondarysearch_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-results-1', 'sw_win_featuredlisting_widget', $this_ci, $install_log, $post_data, array('num_listings'=>'1'), TRUE);
        sw_insert_widget('sidebar-results-1', 'categories', $this_ci, $install_log, $post_data, array('count'=>'1'), TRUE);
        sw_insert_widget('sidebar-results-1', 'sw_win_latestlisting_widget', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
    
        sw_insert_widget('sidebar-1', 'search', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-1', 'categories', $this_ci, $install_log, $post_data, array('count'=>'1'), TRUE);
        sw_insert_widget('sidebar-1', 'widget-right-posts', $this_ci, $install_log, $post_data, array('title'=>'Popular Posts'), TRUE);
        sw_insert_widget('sidebar-1', 'widget-right-ads', $this_ci, $install_log, $post_data, array('title'=>''), TRUE);
        sw_insert_widget('sidebar-1', 'tag_cloud', $this_ci, $install_log, $post_data, array('taxonomy'=>'post_tag', 'title'=>'POPULAR TAGS'), TRUE);
        
        sw_insert_widget('bottom-selio', 'widget-fullwidth-discover', $this_ci, $install_log, $post_data, array(), TRUE);
        sw_insert_widget('bottom-selio-listing', 'widget-fullwidth-discover', $this_ci, $install_log, $post_data, array(), TRUE);
    }
    
    // Insert 5 demo agents
    
    sw_add_demo_user($this_ci, 'ketysprings', 'Kety', 'Springs', '+358468745574', NULL, 'kety@listing-themes.com', 'AGENT','Property Owner', range(1, 9) , 'Vinogradska 14','Zagreb');
    sw_add_demo_user($this_ci, 'tonystark', 'Tony', 'Stark', '+358468740067',NULL, 'tony@listing-themes.com', 'AGENT', 'Douglas and Eleman Agency', range(7, 16), 'Stradun 1b','Dubrovnik ');
    sw_add_demo_user($this_ci, 'agent', 'Tomas', 'Wilkinson', '+358412212472', 'agent', 'pero@listing-themes.com', 'AGENT','Company Agent', range(14, 21), 'Riva 192','Split');
    sw_add_demo_user($this_ci, 'alenwinter', 'Alen', 'Winter', '+358412368123', NULL, 'alen@listing-themes.com', 'AGENT','Douglas and Eleman Agency', range(20, 29), 'Krvavi Most 3','Zagreb');
    sw_add_demo_user($this_ci, 'user', 'Test', 'User','+358412473124', 'user', 'test5@geniuscript.com', 'OWNER','Douglas and Eleman Agency', array(), 'Nova Ves 65','Zagreb');
    
    $titles = array(
        'Steak T-Bone in Varazdin',
        'Blobal Music Event',
        'Ice cream on discount',
        'Vacation transport to Pula',
        'Parking prices changed',
        'Apartment on nice Island',
        'City Guide Book',
        'Places in Zagreb',
        'Croatia Holidays market'
    );
    

    $reviews = "This is simply dummy text of the printing and typesetting industry. That has been the industry
            dummy text ever since the 1500s, when an unknown printer took a galley.";

    $posts_ids = get_posts(array(
        'fields'          => 'ids', // Only get post IDs
        'comment_count' => 0,
        'posts_per_page'  => -1
    ));
    $users_mails = array('kety@listing-themes.com','tony@listing-themes.com','pero@listing-themes.com','alen@listing-themes.com');
    $users = get_users(array('role__not_in' => 'administrator'));
    foreach($users as $user){
        $user_data= $user->data;
        if(in_array($user_data->user_email, $users_mails) !==FALSE)
            $users[]=$user_data;
    }
    foreach ($posts_ids as $id) {
        $time = current_time('mysql');
        $user_id = array_rand($users);
        $data = array(
            'comment_post_ID' => $id,
            'comment_author' => $users[$user_id]->display_name,
            'comment_author_email' => $users[$user_id]->user_email,
            'comment_author_url' => 'http://',
            'comment_content' => 'Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio.',
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );
        wp_insert_comment($data);

        $time = current_time('mysql');
        if(isset($users[$user_id+1])) {
            $user_id = $user_id+1;
        } else {
            $user_id = 2;
        }

        $data = array(
            'comment_post_ID' => $id,
            'comment_author' => $users[$user_id]->display_name,
            'comment_author_email' => $users[$user_id]->user_email,
            'comment_author_url' => 'http://',
            'comment_content' => 'Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio.',
            'comment_type' => '',
            'comment_parent' => 0,
            'user_id' => $user_id,
            'comment_author_IP' => '127.0.0.1',
            'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
            'comment_date' => $time,
            'comment_approved' => 1,
        );
        wp_insert_comment($data);

    }
    /* end comments */
        
}

function sw_insert_demo_post($title=NULL, $content=NULL, $attach_id=NULL, $id_imgs=array(), $multipurpose='', $categories_id = array(),$post_image, $tags = array())
{
    global $user_ID;
    
    if(empty($title))
        $title = 'Lorem ipsum dolor sit';
        
    $url_img1 = wp_get_attachment_image_src($id_imgs[rand(1,4)], 'thumbnail');
    $url_img2 = wp_get_attachment_image_src($id_imgs[rand(5,8)], 'thumbnail');
    $url_img3 = wp_get_attachment_image_src($id_imgs[rand(9,11)], 'thumbnail');
    
    // Insert 11 demo posts
    $id_img_gal = $id_imgs;
    shuffle($id_img_gal);
    $id_img_gal = array_slice($id_img_gal, 0,6);
    $post_image_src = wp_get_attachment_image_src($post_image, 'full');
    if(empty($content))
        $content = '[gallery ids="'.join(',', $id_img_gal).'"]<div class="">
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat nec purus eget porta. Aliquam ebendum erat. Donec dui eros, tincidunt at felis non, tristique aliquet ex. Aenean luctus, orci condimentum cursus, quam lorem vulputate ligula, ac pretium risus metus non est. Cras rutrum dolor in tortor ultrices, ullamcorper finibus magna sollicitudin. Vivamus sed massa sit amet diam porta dignissim at in lorem. In facilisis quis erat at tempus. Aliquam semper diam mollis mollis. Mauris dictum, ante ac interdum.</p>
                        <p> Astibulum, nibh ipsum condimentum felis, quis luctus nisi nisl sed orci. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed tempus puet rutrum ultrces. Cras pretium pretium odio aliquam tortor interduma. Morbi commodo egestas mauris, et porttitor ipsum iaculis fermentum. Phasellus ante nibh, posuere gravida odio mattis cursus. </p>
                        <blockquote>Donec sapien odio, mollis ut phaliquet hendrerit erat. Etiam mollis odio ac libero ultrices cursus. Mauris massa felis, rutrum vitae velit et. Aliquam ac neque in dui eleifend elementum vitae mi.</blockquote>
                        <p>Praesent bibendum eget justo ac volutpat. Proin laoreet hendrerit porttitor. Praesent ac lobortis urna. Nam vi ligula nec posuere ornare. Integer aliquet libero at lectus scelerisque fermentum. Sed dapibus massa ut ex semper porttitor. Donec blandit dui sit amet nunc sagittis, ut convallis ligula tempor. Vestibulum at tincidunt mi. Proin venenatis dui et ex lobortis ultricies. </p>
                        <div class="blg-dv">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="blg-sm">
                                        <img src="'.esc_url($post_image_src[0]).'" alt="blog">
                                    </div><!--blg-sm end-->
                                </div>
                                <div class="col-lg-6">
                                    <div class="blg-info">
                                        <p>Orci varius natoque penatibus et magnis disa parturient montes, nascetur ridiculus mus. Vestibulum scelerisque commodo ultricies. Phasellus vite ipsum eget diam feme ntum tempor quis nec diam. Nulla at lacus consequat.</p>
                                        <p> Turpis elementum luctus. Fusce viver erat eget mi conse ctetur pretium. Praesent tellus nulla, placerat at elit into, aliquet hendrit est. Phasellus tellus dui, scelerisque eget tortor molestie, dignissim bibendum enim. Nunc ut ante a nunc sollicitudin venenatis. Integer vehicula mi digsim.</p>
                                        <p> Nunc imperdiet, non sollicitudi lus facilisis. Morbi egestas nisi a interdum eleum. Ut sit amet rhoncus ligula. Integer massa orci, laoreet hendrerit aliquet ne congue eu nibh. </p>
                                    </div><!--blg-info end-->
                                </div>
                            </div>
                        </div><!--blg-dv end-->
                        <p>Ut egestas fringilla commodo. Phasellus ac mi vel massa mattis elementum non et quam. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent at nibh eros. Curabitur rutrum fermentum augue, ut auctor elit tempor scelerisque. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus sed ante eu justo feugiat fringilla sit amet quis arcu. Vivamus eget cursus ligula, condimentum feugiat velit, a viverra urna placerat et.</p>
                        <ul class="bg-links">
                            <li>Nunc varius varius dolor, sit amet dignissim ligula placerat ullamcorper quam a magna tempus ornare. </li>
                            <li>Aliquam sapien lorem, aliquet consequat neque vel, placerat euismod isl vitae velit elementum aliquet.</li>
                            <li>Sed id orci laoreet, lacinia ligula eget, fringilla metus. Quisque nec or condimentum accumsan neque. </li>
                        </ul><!--bg-links end-->
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec volutpat nec purus eget porta. Aliquam ebendum erat. Donec dui eros, tincidunt at felis non, tristique aliquet ex. Aenean luctus, orci condimentum cursus, quam lorem vulputate ligula, ac pretium risus metus non est. Cras rutrum dolor in tortor ultrices, ullamcorper finibus magna sollicitudin. Vivamus sed massa sit amet diam porta dignissim at in lorem. In facilisis quis erat at tempus. Aliquam semper diam mollis mollis. Mauris dictum, ante ac interdum.</p>
                        <p> Astibulum, nibh ipsum condimentum felis, quis luctus nisi nisl sed orci. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed tempus puet rutrum ultrces. Cras pretium pretium odio aliquam tortor interduma. Morbi commodo egestas mauris, et porttitor ipsum iaculis fermentum. Phasellus ante nibh, posuere gravida odio cursus risus. </p>
                    </div>';
    
    $new_post = array(
        'post_title' => $title,
        'post_content' => sw_win_clear_text($content),
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => 5,
        'post_type' => 'post',
        'tags_input' => $tags,
        'post_category' => array($categories_id[rand(0, selio_plugin_call::sw_count($categories_id)-1)]),
        'page_template' => 'template-post-open.php'
    );
    
    $post_id = wp_insert_post($new_post);
    
    if(!empty($attach_id))
        set_post_thumbnail( $post_id, $attach_id );

    return $post_id;
}

function sw_insert_demo_post_offers($title=NULL, $content=NULL, $attach_id=NULL)
{
    global $user_ID;
    
    if(empty($title))
        $title = 'Lorem ipsum dolor sit';
        
    $id_img1 = sw_add_wp_image(get_template_directory().'/demo_content/images/news-'.rand(1,4).'.jpg');
    $id_img2 = sw_add_wp_image(get_template_directory().'/demo_content/images/news-'.rand(5,8).'.jpg');
    $id_img3 = sw_add_wp_image(get_template_directory().'/demo_content/images/news-'.rand(9,12).'.jpg');

    $url_img1 = wp_get_attachment_image_src($id_img1, 'thumbnail');
    $url_img2 = wp_get_attachment_image_src($id_img2, 'thumbnail');
    $url_img3 = wp_get_attachment_image_src($id_img3, 'thumbnail');
    
    // Insert 12 demo posts
    $id_img_gal = array();
    for($i=9;$i>=1;$i--)
    {
        $id_img_gal[] = sw_add_wp_image(get_template_directory().'/demo_content/images/offers-'.$i.'.jpg');
    }
    
    shuffle($id_img_gal);
    $id_img_gal = array_slice($id_img_gal, 0,6);
    if(empty($content))
        $content = '[gallery ids="'.join(',', $id_img_gal).'"]<div class="">
                        <p>
                            Donec faucibus erat non ligula semper, in accumsan quam blandit. Morbi accumsan a urna et viverra. Nam gravida sed ante sit amet maximus. Nulla metus nibh, ultrices vel libero id, ornare mollis arcu. Nulla risus diam, accumsan eget nulla at, pharetra dapibus augue. Nunc ac ultrices sem. Donec et ligula conval lis turpis aliquet rutrum. Maecenas vulputate elementum nisl a molestie. Morbi scelerisque varius dolor eget euismod metus tincidunt nec. Duis ut feugiat velit. Pellentesque eget nisl a nulla fringilla pulvinar at viverra turpis. Donec sed egestas nunc, luctus dictum magna.
                        </p>
                        <p>
                            Curabitur molestie eu risus sit amet efficitur. Phasellus at nisi euismod, suscipit nisl in, venenatis nibh. Vestibulum iaculis ipsum vitae auctor feugiat. Aliquam est urna, consectetur non feugiat nec, placerat ac magna. Donec finibus dui vitae vehicula dictum. Etiam lacinia in lorem ac semper. Donec ullamcorper sem mi, vel tempor ex pellentesque sit amet. Morbi consequat consequat turpis. Duis et interdum elit, ac fauc ibus ipsum. Curabitur rhoncus, risus vitae maximus semper, leo elit congue ligula, ut bibendum felis massa et tellus. Integer ut ipsum sollicitudin, varius risus sed, auctor enim. Donec nec elit viverra, ultricie ante et, pretium sem.
                        </p>
                        <p>
                            Fusce vel faucibus eros. Aenean tempus elit et tincidunt faucibus. Aliquam erat volutpat. Sed non lectus ut eros tempus faucibus. Aliquam pharetra leo vel dolor aliquam gravida. Praesent eget lobortis mauris. Donec placerat nisl non nibh porta tempus.
                        </p>
                        <p>
                            Quisque at mauris est. Vivamus vitae justo at turpis gravida scelerisque. Etiam maximus eget risus sed mollis. Duis non odio eget sapien scelerisque ullamcorper. Vivamus tincidunt scelerisque massa, sit amet fermentum ante viverra finibus. Nam luctus enim sed mattis imperdiet. Vestibulum eget ipsum velit. Mauris ultricies leo purus, eget interdum felis dignissim eu. Etiam vitae laoreet orci. Praesent ac malesua da eros, pellentesque finibus est. Vivamus aliquet efficitur lorem, a porttitor tellus tempor sit amet. Aenean vel vehicula nisi.
                        </p>
                    </div>';
    
                    
    $new_post = array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => $user_ID,
        'post_type' => 'sw-offers',
        /*'comment_status' => 'closed',*/
        'post_category' => array(1,2),
        'page_template' => 'template-post-open.php'
    );
    
    $post_id = wp_insert_post($new_post);

    add_post_meta($post_id, 'badge_title', 'SALE', true);
    add_post_meta($post_id, 'price_before', '$'.rand(100, 200), true);
    add_post_meta($post_id, 'price_now', '$'.rand(50, 100), true);
    
    if(!empty($attach_id))
        set_post_thumbnail( $post_id, $attach_id );

    return $post_id;
}

function sw_generate_map_demo($field_id=NULL, $svg_name=NULL)
{
    
    $added_ids= array();
    
    $CI = &get_instance();
    $CI->load->model('listing_m');
    $CI->load->model('treefield_m');
    
    /* clear */
    $locations_list_vl_0 = $CI->treefield_m->get_by(array('field_id'=>$field_id, 'parent_id'=>0));
    if(!empty($locations_list_vl_0))
    foreach ($locations_list_vl_0 as $key => $value) {
        $CI->treefield_m->delete($value->idtreefield);
    }
    
    // @codingStandardsIgnoreStart
    $svg_path = SW_WIN_GEOMAP_PLUGIN_PATH.'/svg_maps/';
    /* custom map */
    //$svg_path = get_template_directory().'/demo_content/images/';
    global $wp_filesystem;
    // Initialize the WP filesystem, no more using 'file-put-contents' function
    if (empty($wp_filesystem)) {
        require_once (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
    // @codingStandardsIgnoreEnd
    
    $svg =  $wp_filesystem->get_contents($svg_path.$svg_name);
 
    $root_name ='';
    $match = '';
    preg_match_all('/(data-title-map)=("[^"]*")/i', $svg, $match);
    if(!empty($match[2])) {
        $root_name = trim(str_replace('"', '', $match[2][0]));
    } else if(stristr($svg, "http://amcharts.com/ammap") != FALSE ) {
        $root_name = 'undefined';
        $match='';
        preg_match_all('/(SVG map) of ([^"]* -)/i', $svg, $match2);
        if(!empty($match2) && isset($match2[2][0])) {
            $title = str_replace(array(" -","High","Low"), '', $match2[2][0]);
           $root_name = trim($title);
        }
    }
    
    
    $data = array
        (
            'parent_id' => 0,
            'template' => 'treefield_treefield',
            'level'=>0,
            'field_id'=>$field_id
        );

    $selio_langs_object = sw_get_languages();
    $data_lang= array();
    foreach ( $selio_langs_object as $key => $value) {
        $data_lang['value_'.$value['id']] = $root_name;
    }

    $treefield_root_id = $CI->treefield_m->save_with_lang($data, $data_lang);
    $added_ids[] = $treefield_root_id;
    
    $treefield_array = array();
    $treefield_lvl_0_id = array();

    $dom = new DOMDocument();
    $dom->preserveWhiteSpace = false; 
    $dom->formatOutput = true; 
    $dom->loadXml($svg);

    /* set version */
    $root_svg = $dom->getElementsByTagName('svg')->item(0);
    $root_svg->setAttribute('data-sw_geomodule-version', '2.0');

    $paths = $dom->getElementsByTagName('path'); //here you have an corresponding object
        foreach ($paths as $path) {
            $lvl_1 = $path->getAttribute('data-name');
            if($lvl_1 && !empty($lvl_1)){
                $lvl_1 = trim($lvl_1);

                $data = array(
                    'parent_id' => $treefield_root_id,
                    'template' => 'treefield_treefield',
                    'field_id'=>$field_id,
                );

                $data_lang= array();
                foreach ( $selio_langs_object as $lang_ob) {
                    $data_lang['value_'.$lang_ob['id']] = $lvl_1;
                }
                $treefield_id = $CI->treefield_m->save_with_lang($data, $data_lang);
                $treefield_data_dynamic[] = $treefield_id;
                $added_ids[] = $treefield_id;
                
                $path->setAttribute('data-name-lvl_0', $root_name);
                $path->setAttribute('data-name-lvl_1', $lvl_1);
                $path->setAttribute('data-name', $lvl_1.', '.$root_name);

                $path->setAttribute('data-id-lvl_0', $treefield_root_id);
                $path->setAttribute('data-id-lvl_1', $treefield_id);
                $path->setAttribute('data-idtreefield',$treefield_id);
                $treefield_array[]=$lvl_1;
            }
        }     

    $g = $dom->getElementsByTagName('g'); //here you have an corresponding object
        foreach ($g as $path) {
            $lvl_1 = $path->getAttribute('data-name');
            if($lvl_1 && !empty($lvl_1)){
                $lvl_1 = trim($lvl_1);

                $data = array(
                    'parent_id' => $treefield_root_id,
                    'template' => 'treefield_treefield',
                    'field_id'=>$field_id,
                );
                $data_lang= array();
                foreach ( $selio_langs_object as $lang_ob) {
                    $data_lang['value_'.$lang_ob['id']] = $lvl_1;
                }
                $treefield_id = $CI->treefield_m->save_with_lang($data, $data_lang);
                $treefield_data_dynamic[] = $treefield_id;

                $path->setAttribute('data-name-lvl_0', $root_name);
                $path->setAttribute('data-name-lvl_1', $lvl_1);
                $path->setAttribute('data-name', $lvl_1.', '.$root_name);

                $path->setAttribute('data-id-lvl_0', $treefield_root_id);
                $path->setAttribute('data-id-lvl_1', $treefield_id);
                $path->setAttribute('data-idtreefield',$treefield_id);
                $treefield_array[]=$lvl_1;
            }
        }  

    $svg = $dom->saveXML();
    global $wp_filesystem;
    $wp_filesystem->put_contents(sw_win_upload_path().'/files/current_map.svg', $svg);

    $results_obj_id = $CI->listing_m->get();

    if($results_obj_id and !empty($results_obj_id))
        foreach ($results_obj_id as $key => $estate_id) {
            $estate_id = $estate_id->idlisting;
            $data = array();
            
            
            $random_region = $treefield_data_dynamic[array_rand($treefield_data_dynamic)];

            if($key<4) {
                $random_region = $treefield_data_dynamic[0];
            }
            elseif($key<9) {
                $random_region = $treefield_data_dynamic[1];
            }
            elseif($key<12) {
                $random_region = $treefield_data_dynamic[2];
            }
            elseif($key<18) {
                $random_region = $treefield_data_dynamic[3];
            }
            
            $data['location_id'] = $random_region;  

            $CI->listing_m->save($data, $estate_id);
        }
        
    return $added_ids;
}

function sw_insert_widget($sidebar_id, $widget_name, &$this_ci, &$install_log, &$post_data, $widget_options_new = array(), $skip_enabled=false)
{
    static $sidebar_cleared = array();
    
    $sidebars_widgets = get_option( 'sidebars_widgets' );
    
    // into header-2, insert top map and primarysearch
    
    //$sidebar_id = 'header-2';
    
    if($post_data['remove_widgets'] == 1 && !isset($sidebar_cleared[$sidebar_id]))
    {
        $sidebar_cleared[$sidebar_id] = 1;
        
        $sidebars_widgets[$sidebar_id] = array();
        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
        
        //$install_log.= '<div class="alert alert-success" role="alert">Old widgets removed from '.$sidebar_id.'</div>';
    }
    
    //$widget_name = 'sw_win_primarysearch_widget';
    $widget_options = get_option('widget_'.$widget_name);
    $widget_options[] = array('title'=>'');
    
    $widget_options[] = $widget_options_new;

    end( $widget_options );
    $new_widget_id_number = key( $widget_options );
    
    //selio_dump($widget_options);
    
    // [Check and skip import if found]
    $skip_widget_import = false;
    if(isset($sidebars_widgets[$sidebar_id]))
    foreach($sidebars_widgets[$sidebar_id] as $val)
    {
        if(strpos($val, $widget_name) !== false)
            $skip_widget_import = true;
    }
    
    if($skip_widget_import && !$skip_enabled)
    {
        $install_log.= '<div class="alert alert-warning" role="alert">Widget import skipped, '.$widget_name.' found in '.$sidebar_id.'</div>';
        return FALSE;
    }
    // [/Check and skip import if found]

    if(isset($sidebars_widgets[$sidebar_id]) && !in_array($widget_name.'-'.$new_widget_id_number, $sidebars_widgets[$sidebar_id])) { //check if sidebar exists and it is empty
        
        if(empty($sidebars_widgets[$sidebar_id]))
        {
            $sidebars_widgets[$sidebar_id] = array($widget_name.'-'.$new_widget_id_number); //add a widget to sidebar
        }
        else
        {
            $sidebars_widgets[$sidebar_id][] = $widget_name.'-'.$new_widget_id_number;
        }

        update_option('widget_'.$widget_name, $widget_options); //update widget default options
        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
    }
    else // if sidebar doesn't exists'
    {
        $sidebars_widgets[$sidebar_id] = array($widget_name.'-'.$new_widget_id_number); //add a widget to sidebar
        $sidebars_widgets[$sidebar_id][] = $widget_name.'-'.$new_widget_id_number;

        update_option('widget_'.$widget_name, $widget_options); //update widget default options
        update_option('sidebars_widgets', $sidebars_widgets); //update sidebars
    }

    //$install_log.= '<div class="alert alert-success" role="alert">'.$widget_name.' added in '.$sidebar_id.'</div>';
    
    return TRUE;
}

function sw_template_install_plugins(&$this_ci, &$install_log, &$post_data)
{
    //run_activate_plugin( 'revslider/revslider.php' ); //- causing some issues on activation
    //run_activate_plugin( 'js_composer/js_composer.php' );
    //run_activate_plugin( 'SW_Neighborhood_Walker/sw_neighborhood_walker.php' );

    run_activate_plugin( 'elementor/elementor.php' );
    run_activate_plugin( 'elementor-selio/elementor-selio.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Compare/sw_win_classified_compare.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Report/sw_win_classified_report.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Savesearch/sw_win_classified_savesearch.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Pdf/sw_win_classified_pdf.php' );

    run_activate_plugin( SW_WIN_SLUG.'_Currencyconverter/sw_win_classified_currencyconverter.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Dependentfields/sw_win_classified_dependentfields.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Facebooklogin/sw_win_classified_facebooklogin.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Favorites/sw_win_classified_favorites.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Geomap/sw_win_classified_geomap.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Quicksubmission/sw_win_classified_quicksubmission.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Rankpackages/sw_win_classified_rankpackages.php' );
    run_activate_plugin( SW_WIN_SLUG.'_Reviews/sw_win_classified_reviews.php' );
    run_activate_plugin( SW_WIN_SLUG.'_selio_Share/sw_win_selio_share.php' );
}

function selio_run_activate_plugin( $plugin ) {
    
    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );

    if ( file_exists(WP_PLUGIN_DIR.'/'.$plugin) )
    if ( !in_array( $plugin, $current ) ) {
        $current[] = $plugin;
        sort( $current );
        do_action( 'activate_plugin', trim( $plugin ) );
        update_option( 'active_plugins', $current );
        do_action( 'activate_' . trim( $plugin ) );
        do_action( 'activated_plugin', trim( $plugin) );
    }

    return null;
}

function selio_clear_wpautop ($text) {
    $text = str_replace(array("\r\n", "\r", "\n", "\t",'  ','   ',), '', $text);
    return $text;
}

/* Elementor Import */

// sw_elementor_import_templates(esc_html(LOCAL_THEMEROOT) . '/demo_content/elementor/');

function sw_elementor_import_templates($path, $default_template = 'elementor_canvas')
{

    $templates = array();

    if (is_dir($path)){
        if ($dh = opendir($path)){
          while (($file = readdir($dh)) !== false){
              if(strrpos($file, ".json") !== FALSE)
              {
                $file_name = pathinfo($path.$file, PATHINFO_FILENAME);
                $templates[$file_name] = $path.$file;
              }
          }
          closedir($dh);
        }
      }

    if(count($templates) > 0)
        $importer = new Elementor_Template_Importer(
            $templates,
            $args = array(
                'set_default_template' => True,
                'default_page_template' => $default_template,
            )
        );

}

// sw_elementor_assign($page_id, esc_html(LOCAL_THEMEROOT) . '/demo_content/elementor/header-search-image.json');

function sw_elementor_assign($page_id, $json_template_file)
{
    if(!file_exists($json_template_file) || !class_exists('Elementor\Plugin'))
    {
        return false;
    }

    $page_template =  get_page_template_slug( $page_id );

    add_post_meta( $page_id, '_elementor_edit_mode', 'builder' );

    global $wp_filesystem;
    // Initialize the WP filesystem, no more using 'file-put-contents' function
    if (empty($wp_filesystem)) {
        selio_load_file (ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $string =  $wp_filesystem->get_contents($json_template_file);
    
    $json_template = json_decode($string, true);

    $elements = $json_template['content'];

    $data = array(
        'elements' => $elements,
        'settings' => array('post_status'=>'autosave', 'template'=>$page_template),
    );   
    // @codingStandardsIgnoreStart
    $document = Elementor\Plugin::$instance->documents->get( $page_id, false );
    // @codingStandardsIgnoreEnd
    return $document->save( $data );
}



class Import_Elementor_Template {
    
    public function __construct()
    {
        add_action( 'elementor/init', array($this, 'test_path') );
    }
    public function test_path()
    {
        global $_FILES;
        $_FILES['file']['tmp_name'] = esc_html(LOCAL_THEMEROOT) . '/demo_content/elementor/header-search-image.json';
        $_FILES['file']['name'] = esc_html(LOCAL_THEMEROOT) . '/demo_content/elementor/header-search-image.json';

        //$instance = new Elementor\TemplateLibrary\Source_Local;
        //$instance->import_template();

        //\Elementor\Plugin::instance()->templates_manager->import_template();
        // @codingStandardsIgnoreStart
        $imported = Elementor\Plugin::instance()->templates_manager->import_template();
        foreach( $imported as $template ) {
                 update_post_meta( $template['template_id'],  '_wp_page_template', 'templates/full-width-template.php' );
        }
        // @codingStandardsIgnoreEnd
        unset( $_FILES );
    }
}



if(!function_exists('sw_win_clear_text')){
	function sw_win_clear_text ($text) {
                $text = str_replace(array("\r\n", "\r", "\n", "\t",'  ','   ',), '', $text);
                return $text;
	}
}

?>