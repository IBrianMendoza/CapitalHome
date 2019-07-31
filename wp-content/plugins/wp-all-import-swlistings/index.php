<?php

// 1. Create a new WordPress plugin, and include the rapid-addon.php file.

/*
Plugin Name: Winter Listings WpAllImport
Description: Winter Listings add-on for WP All Import!
Version: 1.0
Author: Sandi Winter
*/


if(!function_exists('sw_win_pluginsLoaded')) {
    return false;
}

if(version_compare(phpversion(), '5.5.0', '<'))
{
    return false;  
}

// [Configuration]

$sw_num_images = 5;
$sw_use_google_api_for_gps = true; // convert address to gps coordinates using google api
$sw_num_levels = 5; // max levels in category or location treefield
$sw_show_ui = false; // show standard wp ui for new post types
$reset_all_posts_transitions = false; // will remove all on export prepare
$sw_strict_sync = false; // disable sw_remove_imported

// [/Configuration]

include "rapid-addon.php";

// Our custom post type function
function create_posttype() {
    global $sw_show_ui;
    
    register_post_type( 'swlistings',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Winter listing' ),
                'singular_name' => __( 'Listing' )
            ),
            'public' => true,
            'has_archive' => true,
            'show_ui'     => $sw_show_ui,
            'rewrite' => array('slug' => 'swlisting'),
            'supports' => array( 'title', 'editor', 'custom-fields' )
        )
    );

    register_post_type( 'swtreefields',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Winter locations' ),
                'singular_name' => __( 'Location/Treefield' )
            ),
            'public' => true,
            'has_archive' => true,
            'show_ui'     => $sw_show_ui,
            'rewrite' => array('slug' => 'swtreefield'),
        )
    );
}

// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

// For export

/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
    add_submenu_page('pmxe-admin-home', __('Prepare Winter listings'), __('Prepare Winter listings'), 'manage_options', 'pmxe_admin_export_sw', 'pmxe_admin_export_sw');
}

/** Step 3. */
function pmxe_admin_export_sw() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    global $reset_all_posts_transitions;

    if(isset($_GET['function']) && $_GET['function'] == 'run_prepare')
    {
        sw_win_load_ci_frontend();
        $CI_sw = &get_instance();
        
        $CI_sw->load->model('treefield_m');
        $CI_sw->load->model('listing_m');
        $CI_sw->load->model('file_m');

        if($reset_all_posts_transitions)
        {
            $mycustomposts = get_posts( array( 'post_type' => 'swlistings', 'numberposts' => -1));
            foreach( $mycustomposts as $mypost ) {
                wp_delete_post( $mypost->ID, true);
            }
            $CI_sw->db->update($CI_sw->listing_m->_table_name, array('transition_id'=>NULL));
            //exit('All posts removed');
        }

        // fetch all fields

        $fields_list = $CI_sw->field_m->get_fields(sw_current_language_id());

        // fetch all listings

        $listing_search = $CI_sw->listing_m->get_pagination_lang(NULL, NULL, sw_current_language_id(), FALSE, NULL);

        echo 'Listings detected: '.count($listing_search).'<br />';

        $languages = sw_get_languages();

        //dump($listing_search);

        foreach($listing_search as $key_l => $listing)
        {

            echo 'Preparing listing: '.$listing->idlisting.', '._field($listing, 10).'<br />';
            
            $listing_vars = get_object_vars($listing);

            $json_listing = json_decode($listing->json_object);

            // remove unnecesary
            unset($listing_vars['image_filename']);

            if($listing->transition_id === NULL)
            {
                //dump($listing);
                

                //dump($json_listing);

                //dump($listing_vars);

                // add post

                // Set the post ID
                
                $post_id = wp_insert_post(
                    array(
                        'comment_status'    =>   'closed',
                        'ping_status'       =>   'closed',
                        'post_author'       =>   1,
                        'post_title'        =>   _field($listing, 10),
                        'post_content'      =>   _field($listing, 13),
                        'post_status'       =>   'publish',
                        'post_type'         =>   'swlistings'
                    )
                );

                echo 'transition_id not found, added: '.$post_id.'<br />';

                foreach($listing_vars as $var_k => $var_l)
                {
                    if($var_k == 'image_repository')
                    {
                        $images = explode('","', $var_l);

                        foreach($images as $img_k => $img)
                        {
                            $img = str_replace('["', '', $img);
                            $img = str_replace('"]', '', $img);

                            add_post_meta($post_id, 'image_'.$img_k, sw_win_upload_dir().'/files/'.$img, true);
                        }

                        //dump($images);
                    }
                    else if($var_k == 'json_object')
                    {
                        $listing_json_vars = get_object_vars($json_listing);

                        foreach($listing_json_vars as $json_k=>$json_v)
                        {
                            add_post_meta($post_id, 's_'.$json_k.'_'.sw_current_language(), $json_v, true);
                        }

                        if(count($languages) > 1)
                        {
                            // for multilanguage
                            foreach($languages as $lang_k=>$lang_v)
                            {
                                if(sw_current_language_id() == $lang_v['id'])continue;

                                $conditions = array('search_idlisting'=>$listing->idlisting, 'search_is_activated'=>1);
                                prepare_frontend_search_query_GET('listing_m', $conditions);
                                $listings_l = $CI_sw->listing_m->get_pagination_lang(1, 0, $lang_v['id']);
                                
                                if(empty($listings_l))continue;

                                $lang_data = $listings_l[0];

                                $json_listing_l = json_decode($lang_data->json_object);
                                $listing_json_vars_l = get_object_vars($json_listing_l);
                                foreach($listing_json_vars_l as $json_k=>$json_v)
                                {
                                    add_post_meta($post_id, 's_'.$json_k.'_'.$lang_v['lang_code'], $json_v, true);
                                }

                            }
                        }


                    }
                    else
                    {
                        add_post_meta($post_id, $var_k, $var_l, true);
                    }
                }

                // set transition_id

                $CI_sw->listing_m->save(array('transition_id'=>$post_id), $listing->idlisting);
            }
            else
            {
                // $listing->transition_id already exists

                $post_id = $listing->transition_id;

                echo 'transition_id found, updated: '.$post_id.'<br />';
               
                // Update the post into the database
                wp_update_post(
                    array(
                        'ID'                => $post_id,
                        'comment_status'    =>   'closed',
                        'ping_status'       =>   'closed',
                        'post_author'       =>   1,
                        'post_title'        =>   _field($listing, 10),
                        'post_content'      =>   _field($listing, 13),
                        'post_status'       =>   'publish',
                        'post_type'         =>   'swlistings'
                ));

                foreach($listing_vars as $var_k => $var_l)
                {
                    if($var_k == 'image_repository')
                    {
                        $images = explode('","', $var_l);

                        foreach($images as $img_k => $img)
                        {
                            $img = str_replace('["', '', $img);
                            $img = str_replace('"]', '', $img);

                            update_post_meta($post_id, 'image_'.$img_k, sw_win_upload_dir().'/files/'.$img, true);
                        }

                        //dump($images);
                    }
                    else if($var_k == 'json_object')
                    {
                        $listing_json_vars = get_object_vars($json_listing);

                        foreach($listing_json_vars as $json_k=>$json_v)
                        {
                            update_post_meta($post_id, 's_'.$json_k.'_'.sw_current_language(), $json_v, true);
                        }

                        if(count($languages) > 1)
                        {
                            // for multilanguage
                            foreach($languages as $lang_k=>$lang_v)
                            {
                                if(sw_current_language_id() == $lang_v['id'])continue;

                                $conditions = array('search_idlisting'=>$listing->idlisting, 'search_is_activated'=>1);
                                prepare_frontend_search_query_GET('listing_m', $conditions);
                                $listings_l = $CI_sw->listing_m->get_pagination_lang(1, 0, $lang_v['id']);
                                
                                if(empty($listings_l))continue;

                                $lang_data = $listings_l[0];

                                $json_listing_l = json_decode($lang_data->json_object);
                                $listing_json_vars_l = get_object_vars($json_listing_l);
                                foreach($listing_json_vars_l as $json_k=>$json_v)
                                {
                                    update_post_meta($post_id, 's_'.$json_k.'_'.$lang_v['lang_code'], $json_v, true);
                                }

                            }
                        }

                    }
                    else if(substr($var_k, 0, 6) != 'field_')
                    {
                        update_post_meta($post_id, $var_k, $var_l, true);
                    }
                }
            }

        }

        echo '<br />COMPLETED, now you can start "New Export"<br />';

    }
    else
    {
        echo '<div class="wrap">';
        echo '<p>Winter listings plugin using non standard tables for better performance, but this tables are not visible to wpallexport plugin.</p>';
        echo '<p>So we need to generate standard wp posts, this will take some space in your database and may slow down website a bit, but is required for wp all export to work.</p>';
        echo '<p>Your configuration in php.ini must be configured for this, high max_execution_time and memory_limit for large number of listings.</p>';
        echo '<p>Click here to start with prepare listings for wpallexport, after that you can use wpallexport in regular way, so clicking on New Export.</p>';
        echo '<p class="submit"><a href="'.admin_url( 'admin.php?page=pmxe_admin_export_sw&function=run_prepare').'" class="button button-primary">Prepare listings for wp all export</a></p>';
        echo '</div>';
    }

}

// END For export


// 2. Initialize your add-on.

$sw_import_addon = new RapidAddon('Winter Listings Add-On', 'winter_listings_addon');

$sw_import_addon_loc = new RapidAddon('Winter Locations Add-On', 'winter_locations_addon');

// 3. Add fields to your add-on.

// More field types supported, described here http://www.wpallimport.com/documentation/addon-dev/text-fields/

if(!sw_classified_installed())return;

if(!isset($_GET['page']) || (substr_count($_GET['page'], 'admin-import') == 0 && substr_count($_GET['page'], 'admin-manage') == 0))return;

sw_win_load_ci_frontend();
$CI_sw = &get_instance();

$CI_sw->load->model('treefield_m');
$CI_sw->load->model('listing_m');
$CI_sw->load->model('file_m');
$CI_sw->load->library('UploadHandler', array('initialize'=>FALSE));
$CI_sw->load->library('ghelper', array());;

// Add top fields

$sw_import_addon->add_field('address', 'Address', 'text');
$sw_import_addon->add_field('lat', 'Gps LAT', 'text');
$sw_import_addon->add_field('lon', 'Gps LON', 'text');
$sw_import_addon->add_field('date_modified', 'Date modified', 'text');
// $sw_import_addon->add_field('transition_id', 'Transition_id', 'text'); // Will be related to post id

$sw_import_addon->add_field(
    'is_primary', 'Is primary',
    'radio',
    array(
        '1' => 'Selected',
        '0' => 'Not selected'
    )
);

$sw_import_addon->add_field('related_id', 'Related id (numeric listing id, if not primary)', 'text');

$sw_import_addon->add_field(
    'is_featured', 'Is featured',
    'radio',
    array(
        '0' => 'Not selected',
        '1' => 'Selected'
    )
);

$sw_import_addon->add_field(
    'is_activated', 'Is activated',
    'radio',
    array(
        '1' => 'Selected',
        '0' => 'Not selected'
    )
);


$sw_import_addon->add_field(
    'user_id', 'User ID (Numeric ID from existing user in database)',
    'text'
);

$sw_tree_table = $CI_sw->treefield_m->get_table_tree(sw_current_language_id(), 1);

$sw_categories = array(
    '' => 'Not selected'
);

foreach($sw_tree_table as $tree_field)
{
    $sw_categories[$tree_field->idtreefield] = $tree_field->visual.$tree_field->value.' ('.$tree_field->idtreefield.')';
}

$sw_import_addon->add_field(
    'category_id', 'Category',
    'radio',
    $sw_categories
);

$sw_tree_table_2 = $CI_sw->treefield_m->get_table_tree(sw_current_language_id(), 2);

$sw_locations = array(
    '' => 'Not selected'
);

foreach($sw_tree_table_2 as $tree_field)
{
    $sw_locations[$tree_field->idtreefield] = $tree_field->visual.$tree_field->value.' ('.$tree_field->idtreefield.')';
}

$sw_import_addon->add_field(
    'location_id', 'Location',
    'radio',
    $sw_locations
);

// Load complete dynamic field list

$fields_list = $CI_sw->field_m->get_fields(sw_current_language_id());

foreach(sw_get_languages() as $lang_key=>$lang_row)
foreach($fields_list as $field)
{
    //var_dump($field);
    //var_dump($lang_row);

    $field_id = 'input_'.$field->field_id.'_'.$lang_key;
    $field_name = $field->field_name.' ('.$lang_row['lang_code'].')';

    if($field->type == 'INPUTBOX')
    {
        $sw_import_addon->add_field($field_id, $field_name, 'text');
    }
    elseif($field->type == 'INTEGER')
    {
        $sw_import_addon->add_field($field_id, $field_name, 'text');
    }
    elseif($field->type == 'CATEGORY')
    {
        // Don't applicable for import
    }
    elseif($field->type == 'CHECKBOX')
    {
        $sw_import_addon->add_field(
            $field_id, $field_name,
            'radio',
            array(
                '0' => 'Not selected (0)',
                '1' => 'Selected (1)'
            )
        );
    }
    elseif($field->type == 'TEXTAREA')
    {
        $sw_import_addon->add_field($field_id, $field_name, 'textarea');
    }
    elseif($field->type == 'TREE')
    {
        // TODO:
    }
    elseif($field->type == 'UPLOAD')
    {
        // TODO:
    }
    elseif($field->type == 'DECIMAL')
    {
        $sw_import_addon->add_field($field_id, $field_name, 'text');
    }
    elseif($field->type == 'DROPDOWN_MULTIPLE' || $field->type == 'DROPDOWN')
    {
        $field->values = str_replace('&', '"& char not supported"', $field->values);

        $values_available = explode(',', $field->values);
        $values_available = array_combine($values_available, $values_available);

        $sw_import_addon->add_field(
            $field_id, $field_name,
            'radio',
            $values_available
        );
    }
    elseif($field->type == 'DATETIME')
    {
        // TODO:
    }

}

// For 5 images

$sw_import_addon->add_field('gallery_0', 'Gallery image 0 (also front image), complete URL required', 'text');

for($i=1;$i<$sw_num_images;$i++)
{
    $sw_import_addon->add_field('gallery_'.$i, 'Gallery image '.$i.', complete URL required', 'text');
}

// For locations

$sw_import_addon_loc->add_field(
    'field_id', 'What treefield you want to import? (Field ID)',
    'radio',
    array(
        '1' => 'Categories (1)',
        '2' => 'Locations (2)'
    )
);

$sw_import_addon_loc->add_field('root_id', '*Root treefield ID (Numeric, represents country value for example)', 'text');

for($i=1;$i<=$sw_num_levels;$i++)
{
    $sw_import_addon_loc->add_field('level_id_'.$i, 'Level '.$i.' ID (Numeric, value will be used if empty)', 'text');

    foreach(sw_get_languages() as $lang_key=>$lang_row)
        $sw_import_addon_loc->add_field('level_value_'.$i.'_'.$lang_key, 'Level '.$i.' Value'.' ('.$lang_row['lang_code'].')', 'text');
}


// 4. Register your import function.

$sw_import_addon->set_import_function('sw_listings_import_function');

$sw_import_addon_loc->set_import_function('sw_locations_import_function');

//5. Write your import function.

function sw_locations_import_function( $post_id, $data, $import_options, $article, $logger )
{
  global $sw_import_addon_loc, $CI_sw, $sw_use_google_api_for_gps, $sw_num_levels;

  error_reporting(E_ALL);

  if(empty($data['field_id']) || empty($data['root_id']) || !is_numeric($data['root_id']))
  {
    $sw_import_addon_loc->log('Missing field_id or root_id');
    return;
  }


  $data_s = array();
  $data_s['field_id']= $data['field_id'];
  $data_s['parent_id'] = $data['root_id'];

  // get parent

  $parent = $CI_sw->treefield_m->get_by(array('field_id'=>$data_s['field_id'], 'idtreefield'=>$data_s['parent_id']), true);

  if(!is_object($parent))
  {
    $sw_import_addon_loc->log('parent_id not found: '.$data_s['parent_id'].' or field_id: '.$data_s['field_id']);
    return;
  }

  $parent_level = $parent->level;
  $parent_id = $data_s['parent_id'];
  
  for($i=1;$i<=$sw_num_levels;$i++)
  {
        $l_data = array();
        $insert = false;
        $id = NULL;

        if($i==1)
        {
            $data_s['parent_id'] = $parent_id;
        }
        else
        {
            // Detect parent id

            if(!empty($data['level_id_'.($i-1)]) && is_numeric($data['level_id_'.($i-1)]))
            {
                $data_s['parent_id'] = $data['level_id_'.($i-1)];
            }
            else
            {
                $query = $CI_sw->db->get_where('sw_treefield_lang', array('value'=>$data['level_value_'.($i-1).'_'.sw_current_language_id()]));
                $row = $query->row();
                
                if (isset($row))
                {
                    $sw_import_addon_loc->log('Parent id detected by value: '.$row->treefield_id);
                    $data_s['parent_id'] = $row->treefield_id;
                }
                else
                {
                    $sw_import_addon_loc->log('Parent ID not found by value, skip: '.$data['level_value_'.($i-1).'_'.sw_current_language_id()]);
                    continue;
                }
            }
        }

        if(!empty($data['level_id_'.$i]) && is_numeric($data['level_id_'.$i]))
        {
            $search_row = $CI_sw->treefield_m->get_by(array('field_id'=>$data_s['field_id'], 'idtreefield'=>$data['level_id_'.$i]), true);

            if(!is_object($search_row))
                $id = $data['level_id_'.$i];
        }

        if(empty($id))
        {
            $query = $CI_sw->db->get_where('sw_treefield_lang', array('value'=>$data['level_value_'.$i.'_'.sw_current_language_id()]));
            $row = $query->row();
            
            if (isset($row))
            {
                $sw_import_addon_loc->log('Value found, skip: '.$data['level_value_'.$i.'_'.sw_current_language_id()]);
                continue;
            }
        }

        foreach(sw_get_languages() as $lang_key=>$lang_row)
        {
            if(!empty($data['level_value_'.$i.'_'.$lang_key]) && $data['level_value_'.$i.'_'.$lang_key] != 'NULL')
            {
                $l_data['value_'.$lang_key] = $data['level_value_'.$i.'_'.$lang_key];
                $insert = true;
            }
        }

        if($insert)
        {
            $id = $CI_sw->treefield_m->save_with_lang($data_s, $l_data, $id);
        }
  }

  return true;
}

/*

$data = 
  ["field_37_1"] => string(0) ""
  ["field_57_1"] => string(0) ""
  ["field_5_1"] => string(0) ""
  ["field_20_1"] => string(0) ""
  ["field_19_1"] => string(0) ""
  ["field_7_1"] => string(0) ""
  ["field_9_1"] => string(0) ""
  ["field_22_1"] => string(1) "0"

*/

function sw_listings_import_function( $post_id, $data, $import_options, $article, $logger )
{
  global $sw_import_addon, $CI_sw, $sw_use_google_api_for_gps, $sw_num_images;

  error_reporting(E_ALL);

  $id = NULL;

  // If listing already exists by transition id defined
  $listing_search = $CI_sw->listing_m->get_by(array('transition_id'=>$post_id), true);

  if(count($listing_search) > 0)
    $id = $listing_search->idlisting;

  // Prepare fields

  $data['gps'] = '';

  if(!empty($data['lat']) && !empty($data['lon']) && substr_count($data['lat'], '.') == 1)
    $data['gps'] = number_format($data['lat'], 10, '.', '').', '.number_format($data['lon'], 10, '.', '');
  
  if(empty($data['gps']) && !empty($data['lat']) && 
    substr_count($data['lat'], '.') == 2 && 
    substr_count($data['lat'], ',') == 1)
  {
        $arr_gps = explode($data['lat'], ',');

        $data['gps'] = str_replace(' ', '', $data['lat']);
  }

  // Try to fetch GPS with google api
  if(empty($data['gps']) && !empty($data['address']) && $sw_use_google_api_for_gps)
  {
    $gps = $CI_sw->ghelper->getCoordinates($data['address']);

    if($gps['lat'] == 0)
    {
        // Not found or api calls limit reached
    }
    else
    {
        $data['gps'] = $gps['lat'].', '.$gps['lng'];

        /* [Auto move gps coordinates few meters away if same exists in database] */
        $estate_same_coordinates = $CI_sw->listing_m->get_by(array('gps'=>$data['gps']), TRUE);

        if(is_object($estate_same_coordinates) && !empty($estate_same_coordinates))
        {
            $same_gps = explode(', ', $estate_same_coordinates->gps);
            // $same_gps[0] && $same_gps[1] available
            $rand_lat = rand(1, 9);
            $rand_lan = rand(1, 9);
            
            $data['gps'] = ($same_gps[0]+0.00001*$rand_lat).', '.($same_gps[1]+0.00001*$rand_lan);
        }
        /* [/Auto move gps coordinates few meters away if same exists in database] */

    }
  }

  unset($data['lat'], $data['lon']);
    
  $data['transition_id'] = $post_id;

  if(empty($data['date_modified']))
  {
    $data['date_modified'] = date('Y-m-d H:i:s');
  }
  else
  {
    $data['date_modified'] = date('Y-m-d H:i:s', strtotime($data['date_modified']));
  }

  if(empty($listing_search->date_activated))
    $data['date_activated'] = date('Y-m-d H:i:s');

  // CHECKBOXES contains/split method for xpath arrays

  $fields_list = $CI_sw->field_m->get_fields(sw_current_language_id());
  
  foreach(sw_get_languages() as $lang_key=>$lang_row)
    foreach($fields_list as $field)
    {
        $field_id = 'input_'.$field->field_id.'_'.$lang_key;
        $field_name = $field->field_name;
    
        if($field->type == 'CHECKBOX' && !empty($data[$field_id]))
        {
            if(strlen($data[$field_id]) > 2)
            {
                if(strpos(strtolower($data[$field_id]), strtolower($field_name)) !== false)
                {
                    $data[$field_id] = 1;
                }
                else
                {
                    $data[$field_id] = 0;
                }
            }
        }
    }

  // Image repository Gallery

  if(count($listing_search) > 0)
  {
    $data['repository_id'] = $listing_search->repository_id;
  }
  else if(empty($data['repository_id']))
  {
    $data['repository_id'] = $CI_sw->repository_m->save(array('is_activated'=>1, 'model_name'=>'listing_m'), NULL);
  }

   // Upload images
   if(!empty($data['repository_id']))
   {
        // Remove non-existing files in repository

        $files = $CI_sw->file_m->get_by(array(
            'repository_id' => $data['repository_id']
        ));
        
        foreach($files as $file)
        {
            $filename_exists = false;

            for($i=0;$i<$sw_num_images;$i++)
            {
                if($file->filename == 'r'.$data['repository_id'].$i.'.'.basename($data['gallery_'.$i]))
                    $filename_exists = true;

            }

            if(!$filename_exists)
            {
                $sw_import_addon->log('Remove: '.$file->filename);
                
                if(file_exists(sw_win_upload_path().'files/'.$file->filename))
                    unlink(sw_win_upload_path().'files/'.$file->filename);
                if(file_exists(sw_win_upload_path().'files/thumbnail/'.$file->filename))
                    unlink(sw_win_upload_path().'files/thumbnail/'.$file->filename);

                // Delete all files from db
                $CI_sw->db->where('idfile', $file->idfile);
                $CI_sw->db->delete($CI_sw->file_m->get_table_name()); 
            }

        }
       
        // Add files

        for($i=0;$i<$sw_num_images;$i++)
        {
            if(!empty($data['gallery_'.$i]))
            {
                $file_path = sw_download_image($data['gallery_'.$i], 'r'.$data['repository_id'].$i);

                if($file_path !== false)
                {
                    if(filesize($file_path) > 25000) // if is larger then 25KB only then upload
                    {
                        $file_id = $CI_sw->file_m->save(array(
                            'repository_id' => $data['repository_id'],
                            'order' => $i,
                            'filename' => basename($file_path),
                            'filetype' => pathinfo(basename($file_path), PATHINFO_EXTENSION)
                        ));

                        $CI_sw->uploadhandler->regenerate_versions(basename($file_path), '');
                    }
                    else
                    {
                        $sw_import_addon->log('File to small, skipped: '.$file_path);
                        unlink($file_path);
                    }
                }
            }
        }
    }

  // Save data

  $data_p = $CI_sw->listing_m->array_from_custom($data, $CI_sw->listing_m->get_post_from_rules($CI_sw->listing_m->form_admin));
  
  $data_lang = $CI_sw->listing_m->array_from_custom($data, $CI_sw->listing_m->get_lang_post_fields());

  $id = $CI_sw->listing_m->save_with_lang($data_p, $data_lang, $id);

  $sw_import_addon->log('Listing ID: '.$id);

  return true;
}

//6. Specify when your add-on runs.

$sw_import_addon->disable_default_images();

$sw_import_addon->run(array(
    "post_types" => array( "swlistings" ),
));

$sw_import_addon_loc->disable_default_images();

$sw_import_addon_loc->run(array(
    "post_types" => array( "swtreefields" ),
));

function sw_clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
    return preg_replace('/[^A-Za-z0-9\-\.]/', '', $string); // Removes special chars.
 }

function sw_download_image($url, $wanted_file_name)
{
    global $sw_import_addon;

    $original_name = basename($url);

    // for cases where images are dinamically generated
    if(substr_count($original_name, '&') > 0)
        $original_name = strtok($original_name, '&');

    $original_name = sw_clean($original_name);

    if(substr_count($original_name, '.jpg') == 0 && 
        substr_count($original_name, '.png') == 0 && 
        substr_count($original_name, '.gif') == 0 &&
        substr_count($original_name, '.JPG') == 0 && 
        substr_count($original_name, '.PNG') == 0 && 
        substr_count($original_name, '.GIF') == 0)
        $original_name.='.jpg';

    //return false;
    $file_name = $wanted_file_name.'.'.$original_name;

    $file_path = sw_win_upload_path().'files/'.$file_name;

    if(file_exists($file_path))
    {
        $sw_import_addon->log('File exists, skipped: '.$file_path);
        return false;
    }

    /*

    This part doesn't helping very much...

    $size = sw_remote_filesize($url);
    if(!empty($size) && file_exists($file_path))
    {
        $filesize_web = $size;
        $filesize_local = filesize($file_path);

        if($filesize_web == $filesize_local)return $file_path;
    }

    */
    

    set_time_limit(0);
    //This is the file where we save the    information
    $fp = fopen($file_path, 'w+');
    //Here is the file we are downloading, replace spaces with %20
    $ch = curl_init(str_replace(" ", "%20", $url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // write curl response to file
    curl_setopt($ch, CURLOPT_FILE, $fp); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // get curl response
    
    $ret = false;
    if(curl_exec($ch) === false)
    {
        $sw_import_addon->log('Curl error: ' . curl_error($ch));
        $sw_import_addon->log('For file: '.$url);
    }
    else
    {
        $sw_import_addon->log('Downloaded: '.$url);
        $sw_import_addon->log('To: '.$file_path);

        $ret = $file_path;
    }
    
    curl_close($ch);

    return $ret;
}

function sw_remote_filesize($url) {
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$fp = @fopen($url, 'rb')) {
        return false;
    }
    if (
        isset($http_response_header) &&
        preg_match($regex, implode("\n", $http_response_header), $matches)
    ) {
        return (int)$matches[0];
    }
    return strlen(stream_get_contents($fp));
}

function sw_postlistings_sync( $ids ) {
    global $CI_sw, $post_type;

    foreach($ids as $post_id){
        // do something with post using ID - $post_id

        if(is_numeric($post_id) && get_post_type($post_id) == 'swlistings')
        {
            $listing_search = $CI_sw->listing_m->get_by(array('transition_id'=>$post_id), true);
            
            if(count($listing_search) > 0)
            {
                $id = $listing_search->idlisting;
                $CI_sw->listing_m->delete($id);
            }
    
        }
    }
}

add_action('pmxi_delete_post', 'sw_postlistings_sync', 10, 1);


function sw_remove_imported() {
    global $CI_sw, $post_type,$sw_strict_sync;
    
    if($sw_strict_sync != true) return false;
    
    sw_win_load_ci_frontend();
    $CI =& get_instance();
    $CI->load->model('listing_m');
    $CI->load->model('file_m');
    
    $listings = $CI->listing_m->get_by(array('transition_id !=' =>'NULL'));
    
    foreach( $listings as $listing ) {
        if(empty($listing->transition_id)) continue;
        
        $sw_post = get_post( $listing->transition_id );
        if($sw_post && $sw_post->post_type=='swlistings') continue;
        
        /* if missing post remove listing */
        $CI->listing_m->delete($listing->idlisting);
    }

    if ($handle = opendir(sw_win_upload_path().'files/strict_cache/')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                @unlink(sw_win_upload_path().'files/strict_cache/'.$entry);
            }
        }
        closedir($handle);
    }
    
}


