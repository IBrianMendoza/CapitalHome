<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define('FPDF_FONTPATH', SW_WIN_PDF_PLUGIN_PATH."fpdf/font");
require(SW_WIN_PDF_PLUGIN_PATH.'fpdf/fpdf.php');

//MakeFont(APPPATH.'libraries/fpdf/font/test/TrebuchetMSItalic.ttf','cp1250');

class Pdf extends Fpdf {

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->prefix ='';
        $this->prefix_url ='';
        
        parent::__construct($orientation, $unit, $size);
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

    public function set_image_by_link($url_img, $filename=NULL, $x = '', $y = '', $w = '', $h = '') {
        
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
        
        $this->Image($this->prefix_url.'/files/strict_cache/'.$filename, $x, $y, $w, $h);
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
        if ($lang == 'hr') {
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
        }
        return $_str;
    }

    /*
     * Function add font, if for lang need speacial charset
     * 
     * @param string $lang code lang
     * retur added forn
     */
     private function add_font_prepare($lang = 'en') {
        if ($lang == 'hr') {	
            $this->AddFont('trebuc', '', 'trebuc.php');
            $this->AddFont('trebuc', 'B', 'trebucbd.php');
            $this->AddFont('trebuc', 'BI', 'trebucbi.php');
            $this->AddFont('trebuc', 'I', 'Trebuchet MS Bold Italic.php');
        } elseif ($lang == 'en') {
            $this->AddFont('trebuc', '', 'trebuc.php');
            $this->AddFont('trebuc', 'B', 'trebucbd.php');
            $this->AddFont('trebuc', 'BI', 'trebucbi.php');
            $this->AddFont('trebuc', 'I', 'Trebuchet MS Bold Italic.php');
        } elseif ($lang == 'pl') {
            $this->AddFont('trebuc', '', 'trebuc.php');
            $this->AddFont('trebuc', 'B', 'trebucbd.php');
            $this->AddFont('trebuc', 'BI', 'trebucbi.php');
            $this->AddFont('trebuc', 'I', 'Trebuchet MS Bold Italic.php');
        } elseif ($lang == 'tr' || $lang == 'es') {
            $this->AddFont('verdana', '', 'tr_verdana.php');
            $this->AddFont('verdana', 'B', 'tr_verdana_italik.php');
            $this->AddFont('verdana', 'BI', 'tr_verdana_bold_italik.php');
            $this->AddFont('verdana', 'I', 'tr_verdana_italik.php');
        } else {
        }
				
        return true;
    }

    
    /*
     * Function choose font
     * 
     * @param string $lang code lang
     * retur string font family name, default Arial
     */
    private function fontfamily_prepare($lang = 'en') {
        if ($lang == 'hr') {
            return 'trebuc';
        } elseif ($lang == 'en') {
            return 'trebuc';
        } elseif ($lang == 'pl') {
            return 'trebuc';
        } elseif ($lang == 'tr' || $lang == 'es') {
            return 'verdana';
        } else {
            return 'Arial';
        }
    }

    public function generate_by_listing($listing_id = '', $lang_code = 'en', $api_key = null, $lang_id) {

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

        // some congig
        $fontfamily = $this->fontfamily_prepare($lang_code);
        $textColour = array(0, 0, 0);
        $tableHeaderTopTextColour = array(255, 255, 255);
        $tableHeaderTopFillColour = array(125, 152, 179);
        $tableHeaderTopProductTextColour = array(0, 0, 0);
        $tableHeaderTopProductFillColour = array(143, 173, 204);
        $tableHeaderLeftTextColour = array(99, 42, 57);
        $tableHeaderLeftFillColour = array(184, 207, 229);
        $tableBorderColour = array(50, 50, 50);
        $tableRowFillColour = array(213, 170, 170);
        // end some congig

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

        $this->AddPage();

        // add font for special charset
        $this->add_font_prepare($lang_code);


        $this->SetDisplayMode('fullwidth');

        $this->SetFont($fontfamily, 'B', 16);
        // Title
        $this->Write(6, _ch($_listing->{"input_10_".$language_id},''));
        $this->Ln(8);
        //address
        
        $this->SetFont($fontfamily, '', 13);
        $this->Write(6, $this->charset_prepare($lang_code, _ch($_listing->address)));
        $this->Ln(6);

        // Gps
        $this->Write(6, _ch($_listing->gps));
        $this->Ln(6);

        /* images */
        for ($i = 0; $i < sw_count($images) && $i < 3; $i++) {
            $this->Image(set_url_scheme(_show_img($images[$i], '230x150')), 11 + ($i * 64), 31);
        }
        /* end images */
        
        // description
        if(!empty($images)){
            $this->Ln($this->GetY() + 12);
        }
        $this->Ln(5);
        if(isset($_listing->{"input_13_".$language_id})){
        $this->SetFont($fontfamily, '', 12);
        $this->Write(6, '' . str_replace(array("\r\n", "\r", "\n"), '', strip_tags($_listing->{"input_13_".$language_id})));
        }

        /* Create Overview tanble */
        
        $this->Ln(10);
        if(isset($option_name['option_1'])){
            $this->SetFont($fontfamily, 'B', 14);
                                    if(isset($option_name['option_1'])){
                                            $this->Write(6, '' . $option_name['option_1']);
                                            $this->Ln(10);
                                    }
            $this->SetLeftMargin(11);
            $fill = false;
            $table_category = array();
            if(isset($category['category_options_1'])) {
            foreach ($category['category_options_1'] as $key => $value) {
                $table_category[] = $option_name[$value['option']] . ':' . $value['option_prefix'] . $value['option_value'] . $value['option_suffix'];
            }

            for ($i = 0; $i < sw_count($table_category); $i++) {

                $this->SetFont($fontfamily, '', 8);

                $this->SetTextColor($tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2]);
                $this->SetFillColor($tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2]);

                $this->Cell(63, 10, ( '' . $table_category[$i]), 1, 0, 'C', $fill);
                $fill = !$fill;

                if (isset($table_category[$i + 1])) {
                    $this->SetTextColor($tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2]);
                    $this->SetFillColor($tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2]);
                    $this->Cell(63, 10, ( '' . $table_category[$i + 1]), 1, 0, 'C', $fill);
                    $fill = !$fill;
                }

                if (isset($table_category[$i + 2])) {
                    $this->SetTextColor($tableHeaderLeftTextColour[0], $tableHeaderLeftTextColour[1], $tableHeaderLeftTextColour[2]);
                    $this->SetFillColor($tableHeaderLeftFillColour[0], $tableHeaderLeftFillColour[1], $tableHeaderLeftFillColour[2]);
                    $this->Cell(63, 10, ( '' . $table_category[$i + 2]), 1, 0, 'C', $fill);
                }

                $i++;
                $i++;
                $fill = !$fill;
                $this->Ln(10);
            }
            $this->SetLeftMargin(10);
            }
        }                    
        /* end Create Overview table */
        $this->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
        $this->Ln(10);
        
        /* Indoor amenities */
        if(isset($category['category_options_21'])&&!empty($category['category_options_21'])):
        
            $_count = 1;
            $_title_added = false;				
									
            $value = current($category['category_options_21']);
						
            do {
								$key = str_replace('option_','',$value['option']);
								if( !isset($_listing->{"input_".$key."_".$language_id})) {
									continue;
								}
                if(!$_title_added){                                         
                    $this->SetFont($fontfamily, 'B', 14);
                    if(isset($option_name['option_21'])&&!empty($option_name['option_21'])){
                        $this->Write(6, '' . $option_name['option_21']);
                    }
                    $this->Ln(10);
                    $this->SetLeftMargin(11);
                    $this->SetFont($fontfamily, '', 12);
                    $_title_added = true;
                }
                
                $this->SetLeftMargin(11);
                $this->SetFont($fontfamily, '', 12);                                              
						
                // Create the data cells
                $this->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
                $this->SetFillColor($tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2]);
                $this->SetFont($fontfamily, '', 12);

                $this->Cell(50, 10, ('    ' . $option_name[$value['option']] . ' ' . $this->Image(plugins_url( SW_WIN_SLUG.'/assets/img/checked-icon.jpg'), $this->GetX(), $this->GetY() + 2.5) . '   '), 0, 0, 'L');

                
                $fill = !$fill;
                if ($_count % 4 == 0)
                    $this->Ln(10);

                $_count++;
            }
            while ($value = next($category['category_options_21']));
            $this->SetLeftMargin(10);
        endif;
        /* end Indoor amenities */

        /* outdoor amenities */
        if(isset($category['category_options_52'])&&!empty($category['category_options_52'])):
            $this->Ln(15);

            $_count = 1;
            $_title_added = false;
            $value = current($category['category_options_52']);
            do {
								$key = str_replace('option_','',$value['option']);
								if( !isset($_listing->{"input_".$key."_".$language_id})) {
									continue;
								}
                                                                
                if(!$_title_added){                                         
                    $this->SetFont($fontfamily, 'B', 14);
                    if(isset($option_name['option_52']))
                            $this->Write(6, '' . $option_name['option_52']);
                    else $this->Write(6, '' .__('Category'));
                    $this->Ln(10);
                    $this->SetLeftMargin(11);

                    $this->SetFont($fontfamily, '', 12);
                    $_title_added = true;
                }
						
                // Create the data cells
                $this->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
                $this->SetFillColor($tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2]);
                $this->SetFont($fontfamily, '', 12);

                $this->Cell(50, 10, ('    ' . $option_name[$value['option']] . ' ' . $this->Image(plugins_url( SW_WIN_SLUG.'/assets/img/checked-icon.jpg'), $this->GetX(), $this->GetY() + 2.5) . '   '), 0, 0, 'L');

                $fill = !$fill;
                if ($_count % 4 == 0)
                    $this->Ln(10);

                $_count++;
            }
            while ($value = next($category['category_options_52']));
            $this->SetLeftMargin(10);
        endif;
        /* end outdoor amenities */

        /* Distance */
        if(isset($category['category_options_43'])&&!empty($category['category_options_43'])):

            $_count = 1;
            $_title_added = false;
            $value = current($category['category_options_43']);
            do {
								$key = str_replace('option_','',$value['option']);
								if( !isset($_listing->{"input_".$key."_".$language_id})) {
									continue;
								}
                                                                                                                              
                if(!$_title_added){                                         
                    $this->Ln(15);
                    $this->SetFont($fontfamily, 'B', 14);
                    if(isset($option_name['option_43'])&&!empty($option_name['option_43'])){
                        $this->Write(6, '' . $option_name['option_43']);
                    }
                    else $this->Write(6, '' .__('Category'));
                    $this->Ln(10);
                    $this->SetFont($fontfamily, '', 12);
                    $this->SetLeftMargin(11);
                    $_title_added = true;
                }
						
                // Create the data cells
                $this->SetTextColor($textColour[0], $textColour[1], $textColour[2]);
                $this->SetFillColor($tableRowFillColour[0], $tableRowFillColour[1], $tableRowFillColour[2]);
                $this->SetFont($fontfamily, '', 12);

                $this->Cell(50, 10, ('    ' . $option_name[$value['option']].' '.$value['option_prefix'].$value['option_value'].$value['option_suffix'] . ' ' . $this->Image(plugins_url( SW_WIN_SLUG.'/assets/img/checked-icon.jpg'), $this->GetX(), $this->GetY() + 2.5) . '   '), 0, 0, 'L');

                $fill = !$fill;
                if ($_count % 4 == 0)
                    $this->Ln(10);
                $_count++;
            }
            while ($value = next($category['category_options_43']));
        endif;
        /* end Distance */

        // map
        if (!empty($api_key) && !empty($_listing->gps)) {
            if($this->GetY()>200)   $this->AddPage(); 
            else {
               $this->Ln(10); 
            }
            $this->Ln(0);
            $this->set_image_by_link('http://www.mapquestapi.com/staticmap/v4/getmap?key=' . $api_key . '&zoom=13&center=' . str_replace(' ', '', $_listing->gps) . '&zoom=10&size=715,300&type=map&imagetype=jpeg&pois=1,' . str_replace(' ', '', $_listing->gps) . '', $_listing->gps, $this->GetX() + 0, $this->GetY() + 5);
            $this->Ln(100);
            $this->SetLeftMargin(10);
        }
        
        
     if($this->GetY()>220)       
         $this->AddPage();  
     
        // logo site
        /*$this->setY(-65);*/
        $this->Image($settings['website_logo_url'], $this->GetX() + 158, $this->GetY() + 10, '30');
        // footer

        $agent = $this->CI->listing_m->get_agents($listing_id);
        $websitetitle = get_bloginfo( 'name' );
        // row 1
        $this->SetFont($fontfamily, 'B', 14);
        $this->setX(10);
        if($agent)
            $this->Cell(97, 10, ('' . __('Agent Details') . '  '), 0, 0, 'L');
        else 
            $this->Cell(97, 10, ('    '), 0, 0, 'L');
        
        $this->Cell(0, 10, ('' . $this->charset_prepare($lang_code, $websitetitle). ''), 0, 0, 'R');
        $this->Ln(10);
        
        if($agent) {
            foreach ($agent as $v) {
                $this->SetFont($fontfamily, '', 12);
                $this->Write(6, $this->charset_prepare($lang_code, $v->user_email).' - '.$this->charset_prepare($lang_code, $v->display_name));
                $this->Ln(6);
               
            }
        }
        
        
        // row 2
        /*
        $this->SetFont($fontfamily, '', 12);  
        if($agent)
            $this->Cell(97, 10, ('  ' . __('Name') . ': ' . $this->charset_prepare($lang_code, $agent['name_surname'])), 0, 0, 'L');
        else $this->Cell(97, 10, ('    '), 0, 0, 'L');
       
        $this->Cell(55, 10, (' ' . __('Phone') . ': ' . $this->charset_prepare($lang_code, $settings['phone']) . '  '), 0, 0, 'R');
        $this->Ln(10);
        // row 3
        if($agent)
        $this->Cell(97, 10, ('  ' . __('Phone') . ': ' .$this->charset_prepare($lang_code, $agent['phone'])), 0, 0, 'L');
         else $this->Cell(97, 10, ('    '), 0, 0, 'L');
        $this->Cell(55, 10, (' ' . __('Fax') . ': ' .   $this->charset_prepare($lang_code, $settings['fax']) . '  '), 0, 0, 'R');
        $this->Ln(10);
        // row 4
          if($agent)
             $this->Cell(97, 10, ('  ' . __('Mail') . ': ' .$this->charset_prepare($lang_code, $agent['mail'])), 0, 0, 'L');
          else $this->Cell(97, 10, ('    '), 0, 0, 'L');
        
          $this->Cell(55, 10, (' ' . __('Mail') . ': ' . $this->charset_prepare($lang_code, $settings['email']) . '  '), 0, 0, 'R');
        $this->Ln(10);*/

        $filename='listing_'.$listing_id.'_'.$lang_code.'.pdf';
        $this->Output('I', $filename);
    }

}
