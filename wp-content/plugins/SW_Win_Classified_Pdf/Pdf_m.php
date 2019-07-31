<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(SW_WIN_PDF_PLUGIN_PATH.'/mpdf/vendor/autoload.php');
class Sw_Mpdf extends \Mpdf\Mpdf
{
    
}

class Pdf_m {

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->prefix ='';
        $this->prefix_url ='';
        
        /* include */
        $this->CI = &get_instance();
        $this->CI->load->model('listing_m');
        $this->CI->load->model('field_m');
        $this->CI->load->model('file_m');
        $this->CI->load->model('settings_m');
        $this->CI->load->model('user_m');
        /* end  include */
        $upload_dir= wp_get_upload_dir();
        $this->prefix=  $upload_dir['basedir'].'/sw_win/';
        $this->prefix_url=  $upload_dir['baseurl'].'/sw_win/';
    }

    /*
     * Put remote image 
     * 
     * @param $url_img string link with img
     * @param $x string/int position X
     * @param $y string/int position Y
     * @param $w string/int width of image
     * @param $h string/int height of image
     *      
     */

    public function set_image_by_link($url_img, $filename=NULL) {
        
        if($filename === NULL)
            $filename = time() . rand(000, 999) . '.jpg';
        else {
            $same = explode(', ', $filename);
            $rand_lat = round($same[0], 3);
            $rand_lan = round($same[1], 3);
            $filename = $rand_lat.'x'.$rand_lan;
            $filename = str_replace('.', '_', $filename);
            $filename .='.jpg';
        }
        if(!file_exists($this->prefix.'files/strict_cache/'.$filename)) {
            $f = $this->file_get_contents_curl($url_img);
            file_put_contents($this->prefix.'files/strict_cache/'.$filename, $f);
        }
        
        return $this->prefix_url.'/files/strict_cache/'.$filename;
    }
    
    
    /*
     * Function convert string to requested character encoding
     * 
     * @param string $lang code lang
     * @param string $str string for character encoding
     * retur encoded string;
     */
    public function charset_prepare($lang = 'en', $str) {
        $_str = ' ';
        /*if ($lang == 'hr') {
            //some conversion
            $_str = iconv(mb_detect_encoding($str), 'CP1250//TRANSLIT//IGNORE', html_entity_decode($str));
        } elseif ($lang == 'en') {
            $_str = iconv(mb_detect_encoding($str), 'CP1250//TRANSLIT//IGNORE', html_entity_decode($str));
        } elseif ($lang == 'pl') {
            $_str = iconv(mb_detect_encoding($str), 'CP1250//IGNORE', html_entity_decode($str));
        }else if ($lang == 'tr' || $lang == 'es') {
            //some conversion
            $_str = iconv(mb_detect_encoding($str), 'CP1254//TRANSLIT//IGNORE', html_entity_decode($str));
        } else {
            $_str = $str;
        }*/
        return $str;
    }

    public function generate_by_listing($listing_id = '', $lang_code = 'en', $api_key = null, $lang_id = '') {


        /* data var */

        /* var int id lang */
        $language_id = sw_current_language_id();

        if($lang_id) {
            $language_id = $lang_id;
        }
        
        /* var array website settings */
        $settings = $this->CI->settings_m->get_fields();

        /* var array listing field */
        $_listing = '';

        /* var array listing options */
        $_listing = '';

        /* var array category options */
        $category = '';

        /* var array option names */
        $option_name = '';

        /* var array listing images */
        $images = '';

        /* end data */

        /* listing */
        $where_in = array($listing_id);
        $_listing  = $this->CI->listing_m->get_lang($listing_id, $language_id);
        if (empty($_listing)) {
            exit(__('Listing not found','sw_win'));
        }

        foreach ($_listing as $key => $value) {
            if (is_string($value))
                $_listing->$key = $this->charset_prepare($lang_code, $value);
        }

        /* fetch category */
        $options_name = $this->CI->field_m->get_fields($language_id);
        $category = array();  
        $option_name = array();
        foreach ($options_name as $key => $row) {
            $field = 'input_' . $row->idfield.'_'.$language_id;
            $type = $row->type;
            //skip
            if ($type == 'UPLOAD')
                continue;
            if ($type == 'TABLE')
                continue;
            if ($type == 'PEDIGREE')
                continue;
            if ($type == 'TREE')
                continue;
            /*
            if (!isset($_listing->$field))
                continue;*/
            $option_name['option_' . $row->idfield] = $this->charset_prepare($lang_code, $row->field_name);
            /*if (empty($_listing->$field))
                continue;*/

            // echo $_listing->$field.PHP_EOL;
            if(isset($_listing->$field))
                $category['category_options_' . $row->parent_id][$row->idfield]['option_value'] =  $_listing->$field;
            else
                $category['category_options_' . $row->parent_id][$row->idfield]['option_value'] =  '';
            $category['category_options_' . $row->parent_id][$row->idfield]['option'] = 'option_' . $row->idfield;
            $category['category_options_' . $row->parent_id][$row->idfield]['option_suffix'] = $this->charset_prepare($lang_code, $row->suffix) ;
            $category['category_options_' . $row->parent_id][$row->idfield]['option_prefix'] = $this->charset_prepare($lang_code, $row->prefix) ;
        }
        
        
        /* end fetch category */

        $images = array();
        $_listing->image_repository = json_decode($_listing->image_repository);
        if(!empty($_listing->image_repository))
        foreach ($_listing->image_repository as $key => $value) {
            if (isset($_listing->image_filename)) {
                $images[] = $value;
            }
        }

        /* [START] Fetch logo URL */
        $settings['website_logo_url'] = '';
        if(function_exists('nexos_logo_in_header')) {
            $settings['website_logo_url'] = nexos_logo_in_header();
        }
        /* [END] Fetch logo URL */

        /* end listing */

        
        // START CREATE PDF

        $html = array();
        
        $html['title'] = _ch($_listing->{"input_10_".$language_id});
        $html['address'] = _ch($_listing->address);
        $html['gps'] = _ch($_listing->gps);
        
        $html_images='';
        /* images */
        for ($i = 0; $i < sw_count($images) && $i < 3; $i++) {
            $html_images .='<div><a href="#"><img src="'._show_img($images[$i], '230x150').'" alt=""></a></div>';
        }
        $html['images'] = $html_images;
        /* end images */
        
        
        // description
        $html['description'] = _ch($_listing->{"input_13_".$language_id},'');
        
        /* Create Overview tanble */
        
        $table = '';
        if(isset($option_name['option_1'])){
            $html['option_name_1'] = _ch($option_name['option_1']);
            $table_category = array();
            if(isset($category['category_options_1'])) {
            foreach ($category['category_options_1'] as $key => $value) {
                $table_category[] = $option_name[$value['option']] . ':' . $value['option_prefix'] . $value['option_value'] . $value['option_suffix'];
            }
            
            $c=0;
            $columns = 3;
            $arr=array_values($table_category);           
            $n_max=sw_count($arr);    
            $tr=ceil($n_max/$columns);  

            $table="<table border='0' class='overview'>";

            for($i=0;$i<$tr;$i++)
            {
                $table.="<tr>";
                 for($y=0;$y<$columns;$y++){
                  $even = "";
                  if($c %2 != 0)
                    $even = "class='even'";
                  
                  
                  $table.="<td ".$even.">";
                   if($c<$n_max)
                      $table.= $arr[$c]; 
                    else 
                      $table.='';

                  $table.="</td>";
                  $c++;
                }
                $table.="</tr>";
            }

            $table.="</table>";
            }
        }    
        
        $html['table'] = $table;
        
        /* Indoor amenities */
        $html_options_21 ='';
        if(isset($category['category_options_21'])&&!empty($category['category_options_21'])){
        
            $html_options_21.='<div class="d-table">';
            foreach ($category['category_options_21'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if( isset($_listing->{"input_".$key."_".$language_id}) && $_listing->{"input_".$key."_".$language_id} ==1) {
                    $html_options_21.="<div><img class='d-table-img1' src='".plugins_url( SW_WIN_SLUG.'/assets/img/checked-icon.jpg')."'/>"._ch($option_name[$value['option']])."</div>";
                } else {
                    $html_options_21.="<div><img class='d-table-img2' src='".plugins_url( SW_WIN_SLUG.'/assets/img/cross-remove-sign.png')."'/>"._ch($option_name[$value['option']])."</div>";
                }
            }
            $html_options_21 .='</div>';
            
        }
        $html['option_name_21'] = _ch($option_name['option_21'], '');
        $html['options_21'] = $html_options_21;
        /* end Indoor amenities */
        

        
        /* outdoor amenities */
        $html_options_52 ='';
        if(isset($category['category_options_52'])&&!empty($category['category_options_52'])){
            $html_options_52 .='<div class="d-table">';
            foreach ($category['category_options_52'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if( isset($_listing->{"input_".$key."_".$language_id}) && $_listing->{"input_".$key."_".$language_id} ==1) {
                    $html_options_52.="<div><img class='d-table-img1' src='".plugins_url( SW_WIN_SLUG.'/assets/img/checked-icon.jpg')."'/>"._ch($option_name[$value['option']])."</div>";
                } else {
                    $html_options_52.="<div><img class='d-table-img2' src='".plugins_url( SW_WIN_SLUG.'/assets/img/cross-remove-sign.png')."'/>"._ch($option_name[$value['option']])."</div>";
                }
            }
            $html_options_52 .='</div>';
        }
        $html['option_name_52'] = _ch($option_name['option_52'], '');
        $html['options_52'] = $html_options_52;
        
        /* end outdoor amenities */

        /* Distance */
        $html_options_43 ='';
        if(isset($category['category_options_43'])&&!empty($category['category_options_43'])){
            $html_options_43 .='<div class="d-table">';
            foreach ($category['category_options_43'] as $key => $value) {
                $key = str_replace('option_','',$value['option']);
                if(!empty($value['option_value']))
                    $html_options_43.="<div>".$option_name[$value['option']].' '.$value['option_prefix'].$value['option_value'].$value['option_suffix'] ."</div>";
            }
             $html_options_43 .='</div>';
        }
        $html['option_name_43'] = _ch($option_name['option_43'], '');
        $html['options_43'] = $html_options_43;
        /* end Distance */
        
        // map
        $html['map_img'] ='';
        if (!empty($api_key) && !empty($_listing->gps)) {
            $src = $this->set_image_by_link('http://www.mapquestapi.com/staticmap/v4/getmap?key=' . $api_key . '&zoom=13&center=' . str_replace(' ', '', $_listing->gps) . '&zoom=10&size=715,300&type=map&imagetype=jpeg&pois=1,' . str_replace(' ', '', $_listing->gps) . '', $_listing->gps);
            
            $html['map_img'] = '<img src="'.$src.'" class="map-img" alt="">';
        }
        
        
        
        $agent_details ='';
        $agent = $this->CI->listing_m->get_agents($listing_id);
        if($agent) {
            $agent_details .= '<h3 class="t_title"><b>'.__('Agent Details', 'sw_win').'</b></h3><br/>';
            foreach ($agent as $v) {
                $agent_details .= '<div class="t_items">'._ch($v->user_email).' - '._ch($v->display_name).'</div><br/>';
            }
        }
        $html['agent_details'] = $agent_details;
        
        $websitetitle = get_bloginfo( 'name' );
        $contact_details ='';
        $contact_details .= '<h3 class="t_title"><b>'.$websitetitle.'</b></h3><br/>';
        $contact_details .= '<div class="t_items"><img src="'.$settings['website_logo_url'].'"></img></div><br/>';
        $html['contact_details'] = $contact_details;
        
        
        $filename='listing_'.$listing_id.'_'.$lang_code.'.pdf';
        
        $output = file_get_contents(SW_WIN_PDF_PLUGIN_PATH.'mpdf/listing.html');
        foreach ($html as $key => $value) {
            $output = str_replace('{'.$key.'}', $value, $output);
        }
        
        // uncomment for use only utf-8
        //$mpdf = new Sw_Mpdf(['mode' => 'utf-8', 'format' => 'A4','default_font' => 'XBRiyaz']);
        
        $mpdf = new Sw_Mpdf(['mode' => 'c', 'format' => 'A4','default_font' => 'XBRiyaz']);
        
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->WriteHTML($output);
        $mpdf->Output($filename, 'I');
        exit();
        
    }
    
    
    public function file_get_contents_curl($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set cURL to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}
