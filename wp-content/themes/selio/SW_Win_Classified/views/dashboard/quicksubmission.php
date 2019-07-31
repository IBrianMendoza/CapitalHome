<div class="bootstrap-wrapper quick-submission">

<div class="widget widget-styles clearfix" id="quick_submission_form">
  <div class="content-box">
    
    <?php if(selio_plugin_call::sw_settings('register_page') != NULL && !sw_is_logged_user()): ?>
        <div class="alert alert-info alert-dismissible <?php if (!is_user_logged_in()): ?> login_popup_enabled <?php endif;?>"><a href="<?php echo esc_url(get_permalink(selio_plugin_call::sw_settings('register_page'))); ?>" ><?php echo esc_html__('If you are already registered, please first login here', 'selio'); ?></a></div>
    <?php endif; ?>
    
    <?php if(!allow_submit_listing()):?>
    
            <div class="alert alert-info alert-dismissible"><?php echo esc_html__('Your account don\'t have permission to add listing, you can logout and add listing again', 'selio'); ?></div>

    <?php else: ?>
    
    <?php _form_messages( esc_html__('Thanks on submission and check your email for all details', 'selio')); ?>
    
    <?php 
        if(sw_default_language() != sw_current_language())
        {
            echo '<div class="alert alert-info alert-dismissible">'.esc_html__('On quick submission you should populate fields in default website langauge', 'selio').': '.esc_html(sw_get_language_name(sw_default_language())).'</div>';
        }
    ?>
    
    <form action="<?php echo esc_url(get_permalink(selio_plugin_call::sw_settings('quick_submission'))); ?>#quick_submission_form" class="form-editproperty form-horizontal selio-form" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
        <?php if(!is_user_logged_in()): ?>
        <div class="form-group <?php _has_error('email'); ?> IS-INPUTBOX ">
            <label for="input_email" class="control-label"><?php echo esc_html__('Your email', 'selio'); ?></label>
            <div class="form-field">
                <input class="" id="input_email" name="email" type="text" value="<?php echo esc_attr(_fv('form_widget', 'email')); ?>" placeholder="<?php echo esc_attr_e('Your email', 'selio'); ?>" />
            </div>
        </div><!-- /.form-group -->
        <?php endif; ?>

      <div class="form-group <?php _has_error('address'); ?> IS-INPUTBOX ">
        <label for="input_address" class="control-label"><?php echo esc_html__('Address','selio'); ?></label>
        <div class="form-field">
          <input name="address" value="<?php echo esc_attr(_fv('form_object', 'address')); ?>" type="text" id="input_address" class="" placeholder="<?php echo esc_attr_e('Address','selio'); ?>"/>
        </div>
      </div>


      <div class="form-group <?php _has_error('gps'); ?> IS-INPUTBOX  hidden">
        <label for="input_gps" class="control-label"><?php echo esc_html__('Gps','selio'); ?></label>
        <div class="form-field">
          <input name="gps" value="<?php echo esc_attr(_fv('form_object', 'gps')); ?>" type="text" id="input_gps" class="" readonly="" placeholder="<?php echo esc_attr_e('Gps','selio'); ?>"/>
        </div>
      </div>
      
      <div class="hidden form-group <?php _has_error('date_modified'); ?> IS-INPUTBOX ">
        <label for="input_date_modified" class="control-label"><?php echo esc_html__('Date modified','selio'); ?></label>
        <div class="form-field">
          <input name="date_modified" value="<?php echo esc_attr(_fv('form_object', 'date_modified')); ?>" type="text" id="input_date_modified" readonly="" class="" placeholder="<?php echo esc_attr_e('Date modified','selio'); ?>"/>
        </div>
      </div>
      
      <div class="form-group hidden <?php _has_error('repository_id'); ?> ">
        <label for="input_repository_id" class="control-label"><?php echo esc_html__('Repository','selio'); ?></label>
        <div class="form-field">
          <input name="repository_id" value="<?php echo esc_attr(_fv('form_object', 'repository_id')); ?>" type="text" id="input_repository_id" class="" readonly="" placeholder="<?php echo esc_attr_e('Repository','selio'); ?>"/>
        </div>
      </div>
      
      <?php if(selio_plugin_call::sw_settings('show_categories')): ?>
      
      <div class="form-group <?php _has_error('category_id'); ?> group_category_id ">
        <label class="control-label"><?php echo esc_html__('Category','selio'); ?></label>
        <div class="form-field">
          <?php esc_viewe(form_treefield('category_id', 'treefield_m', esc_attr(_fv('form_object', 'category_id')), 'value', sw_current_language_id(), 'field_', false, '-', 1));?>
        </div>
      </div>
      
      <?php endif; ?>
      
      <?php if(selio_plugin_call::sw_settings('show_locations') && function_exists('show_geomap')): ?>
      
      <div class="form-group <?php _has_error('location_id'); ?> group_location_id ">
        <label class="control-label"><?php echo esc_html__('Location','selio'); ?></label>
        <div class="form-field">
          <?php esc_viewe(form_treefield('location_id', 'treefield_m', esc_attr(_fv('form_object', 'location_id')), 'value', sw_current_language_id(), 'field_', false, '-', 2));?>
        </div>
      </div>
      
      <?php endif; ?>
      
      </div>
      </div>
      
      <div class="row">
      <div class="col-xs-12 col-sm-12">
    <div>
    <?php if(selio_plugin_call::sw_count(sw_get_languages()) > 1): ?>
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; 
      
        // show just current language
        if(sw_default_language() != $row['lang_code'])
        {
            continue;
        }
      ?>
        <li role="presentation" class="<?php ($i==1) ? esc_viewe('active'):''?>"><a href="#lang_<?php echo esc_attr($key); ?>" data-aria-form-field="<?php echo esc_attr($row['lang_code']); ?>" role="tab" data-toggle="tab"><?php echo esc_html($row['title']); ?></a></li>
      <?php  endforeach; ?>
      </ul>
    <?php endif; ?>
        
      <!-- Tab panes -->
      <div class="tab-content">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; 
      
        // show just current language
        if(sw_default_language() != $row['lang_code'])
        {
            continue;
        }
      ?>
      
      
        <div role="tabpanel" class="tab-pane <?php ($i==1) ? esc_viewe('active'):''?>" id="lang_<?php echo esc_attr($key); ?>">

          <div class="field_slug form-group <?php _has_error('input_slug_'.esc_attr($key)); ?>  hidden">
            <label for="input_slug_<?php echo esc_attr($key); ?>" class="control-label"><?php echo esc_html__('Slug', 'selio'); ?></label>
            <div class="form-field">
              <input name="input_slug_<?php echo esc_attr($key); ?>" type="text" value="<?php echo esc_attr(_fv('form_object', 'input_slug_'.esc_attr($key))); ?>" class="" id="input_slug_<?php echo esc_attr($key); ?>" placeholder="<?php echo esc_attr_e('Slug', 'selio'); ?>">
            </div>
          </div>

            <?php foreach($fields_list as $key_field=>$field): ?>

            <?php                    
                
                $required = '';
                if($field->is_required)
                    $required = '*';
                
                if(!$field->is_quickvisible && !$field->is_required) // Don't show if not required on quick submission
                    continue;
                
            ?>
            
            <?php if($field->type == 'CATEGORY'): ?>
            <div class="field_<?php echo esc_attr($field->idfield); ?> ">
            <hr />
            <h4><?php echo esc_html($field->field_name)?></h4>
            <hr />
            </div>
            <?php elseif($field->type == 'INPUTBOX' || $field->type == 'DECIMAL' || $field->type == 'INTEGER'): ?>
            
            <?php
            
            $field_lang = $this->field_m->get_field_data($field->idfield, $key);
            
            $presuf='';
            if(!empty($field_lang))
                $presuf = $field_lang->prefix.$field_lang->suffix;
            ?>
            
          <div class="field_<?php echo esc_attr($field->idfield); ?> form-group <?php _has_error('input_'.esc_attr($field->idfield).'_'.esc_attr($key)); ?> ">
            <label for="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" class="control-label"><?php echo esc_html($required.$field->field_name); ?></label>
            <?php if(empty($presuf)): ?>
            <div class="form-field">
              <input name="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" type="text" value="<?php echo esc_attr(_fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key))); ?>" class="" id="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" placeholder="<?php echo esc_attr($field->field_name); ?>">
            </div>
            <?php else: ?>
                <div class="form-field">
                  <input name="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" type="text" value="<?php echo esc_attr(_fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key))); ?>" class="" id="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" placeholder="<?php echo esc_attr($field->field_name); ?>">
                    <div class="presuf">
                        <?php echo esc_html($presuf); ?>
                    </div>
                </div>
            <?php endif; ?>
          </div>
          
            <?php elseif($field->type == 'TEXTAREA'): ?>
            
          <div class="field_<?php echo esc_attr($field->idfield); ?> form-group <?php _has_error('input_'.esc_attr($field->idfield).'_'.esc_attr($key)); ?> ">
            <label for="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" class="control-label"><?php echo esc_html($required.$field->field_name); ?></label>
            <div class="form-field">
              <?php
                $settings = array( 
                    'textarea_rows' => 5,
                    'textarea_name' => "input_".esc_html($field->idfield.'_'.esc_attr($key)), 
                    );
                $editor_id = "input_".esc_html($field->idfield.'_'.esc_attr($key));
                $content = esc_attr(_fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key)));
                wp_editor( $content, $editor_id, $settings );
              ?>
            </div>
          </div>
          
            <?php elseif($field->type == 'DROPDOWN' || $field->type == 'DROPDOWN_MULTIPLE'): ?>
            
            <?php
                $field_lang = $this->field_m->get_field_data($field->idfield, $key);
                $values_available = explode(',', $field_lang->values);
                $values_available = array_combine($values_available, $values_available);
            
            ?>
        <div class="form-group">
            <div class="form-field">
                <div class="drop-menu">
                    <div class="select">
                        <span><?php echo esc_html('Any','selio');?></span>
                        <i class="fa fa-angle-down"></i>
                    </div>
                    <input name="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" type="hidden" value="<?php echo esc_attr(_fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key))); ?>" class="" id="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>">
                    <ul class="dropeddown">
                        <?php if(selio_plugin_call::sw_count($values_available)>0) foreach ($values_available as $key => $value):?>
                            <li data-value="<?php echo esc_attr($value);?>"><?php echo esc_html($value);?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>

          
            <?php elseif($field->type == 'CHECKBOX'): ?>

            <div class="input-field checkbox-field field_<?php echo esc_attr($field->idfield); ?> <?php _has_error('input_'.esc_attr($field->idfield).'_'.esc_attr($key)); ?>" >
                <input type="checkbox"  name="<?php echo esc_attr( 'input_'.esc_attr($field->idfield).'_'.esc_attr($key)); ?>" id="inputIsLocked_<?php echo esc_attr($field->idfield); ?>" value="1" <?php echo esc_attr(_fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key), 'CHECKBOX')); ?>>
                <label for="inputIsLocked_<?php echo esc_attr($field->idfield); ?>" >
                    <span></span>
                    <small><?php echo esc_html($required.$field->field_name); ?></small>
                </label>
            </div>
            
            
            <?php elseif($field->type == 'TABLE'): ?>
               <div class="field_<?php echo esc_attr($field->idfield); ?> form-group <?php _has_error('input_'.esc_attr($field->idfield).'_'.esc_attr($key)); ?> row">
                    <label for="input_<?php echo esc_attr($field->idfield.'_'.esc_attr($key)); ?>" class="control-label"><?php echo esc_html($required.$field->field_name); ?></label>
                    <div class="form-field">
                        <?php 
                            $field_lang = $this->field_m->get_field_data($field->idfield, $key);
                            $columns = explode(',', $field_lang->values);
                        ?>
                        <?php esc_viewe(form_table('input_'.esc_attr($field->idfield).'_'.esc_attr($key), $columns, _fv('form_object', 'input_'.esc_attr($field->idfield).'_'.esc_attr($key))));?>
                    </div>
              </div>
      
            <?php else: ?>
                <?php selio_dump($field); ?>
            <?php endif; ?>
            
            <?php endforeach; ?>
        
        </div>
        <?php endforeach; ?>
      </div>
    
    </div>
      </div>
      </div>
        <?php if (selio_plugin_call::sw_settings('terms_link')): ?>
        <div class="form-group  row">
          <div class="col-md-offset-2 form-field">
                <input name="option_agree_terms" value="true" type="checkbox" id="inputOption_terms"/>
                <label for="inputOption_terms" class="control-label"><a target="_blank" href="<?php echo esc_url(selio_plugin_call::sw_settings('terms_link')); ?>"><?php echo esc_html__('I Agree To The Terms & Conditions', 'selio'); ?></a></label>
          </div>
        </div>
        <?php endif; ?>
        
      <?php if(function_exists('sw_win_load_ci_function_rankpackages') && isset($rank_packages) && selio_plugin_call::sw_count($rank_packages)>0): ?>
      <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <hr />
        <h4><?php echo esc_html__('Rank package', 'selio'); ?></h4>
        <hr />
        
        <p>
        <?php echo esc_html__('Purchase higher listing rank and sell faster', 'selio'); ?>
        </p>
        <table class="table table-striped">
        <thead>
            <tr>
                <th class="w20"></th>
                <th><?php echo esc_html__('Package name', 'selio');?></th>
                <th class="text-center"><?php echo esc_html__('Days expire', 'selio');?></th>
                <th class="text-right"><?php echo esc_html__('Price', 'selio');?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rank_packages as $key=>$rank_package): 
            $selected = $key == 0 || (isset($_POST['packagerank']) && $_POST['packagerank'] == $rank_package->idpackagerank);
        ?>
            <tr>
                <td><?php esc_viewe(form_radio('packagerank', $rank_package->idpackagerank, $selected, 'class="display-inline"')); ?></td>
                <td><strong><?php echo esc_html($rank_package->package_name); ?></strong></td>
                <td class="text-center"><?php ($rank_package->package_days==0) ? esc_viewe('-') : esc_viewe($rank_package->package_days); // WPCS: XSS ok, sanitization ok.?></td>
                <td class="text-right"><?php echo esc_html($rank_package->package_price.' '.selio_plugin_call::sw_settings('default_currency')); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        </table>
      </div>
      </div>
      <?php endif; ?>
      
      <input class="hidden" id="widget_id" name="widget_id" type="text" value="quick_submission" />
      <?php if(!is_user_logged_in() && selio_plugin_call::sw_settings('recaptcha_site_key') !== FALSE && selio_plugin_call::sw_settings('recaptcha_site_key') !== NULL): ?>
        <hr />
          <div class="form-group row">
            <label class="control-label"><?php echo esc_html__('Recaptcha', 'selio'); ?></label>
            <div class="form-field">
              <?php esc_viewe(_recaptcha(false)); ?>
            </div>
          </div>
      <?php endif; ?>

      <hr class="mt15" />
      
      <div class="form-group row">
        <div class="col-sm-offset-2">
          <button type="submit" class="btn-default"><?php echo esc_html__('Save', 'selio'); ?></button>
        </div>
      </div>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php if(allow_submit_listing()):?>
<div class="widget widget-styles">
<div class="widget-title">
    <h2><?php echo esc_html__('Photo and other files','selio'); ?></h2>
</div>
  <div class="content-box">
<div class="upload-files-widget" id="upload-files-<?php echo esc_attr($repository_id); ?>" rel="listing_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="#<?php echo esc_url(admin_url("admin.php?page=listing_manage")); ?>" /></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar celarfix">
            <div class="col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo esc_html__('Add files...', 'selio')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo esc_html__('Delete selected', 'selio')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="col-md-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="bar" style="width:0%;"></div>
                </div>
                <!-- The extended global progress information -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The loading indicator is shown during file processing -->
        <div class="fileupload-loading"></div>
        <div class="clear"></div>
        <!-- The table listing the files available for upload/download -->

          <div role="presentation" class="fieldset-content">
              
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php foreach($this->file_m->get_repository($repository_id) as $file ):?>
<?php sw_add_file_tags($file); ?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo esc_attr($file->filename); ?>" data-src="<?php echo esc_url($file->thumbnail_url); ?>" src="<?php echo esc_url($file->thumbnail_url); ?>">
                </div>
                <div class="filename">
                    <code><?php echo esc_html(character_hard_limiter($file->filename, 20))?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo esc_url($file->download_url)?>" title="<?php echo esc_url($file->filename); ?>" download="<?php echo esc_url($file->filename); ?>" class="zoom-button btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>                  
                    <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo esc_url($file->filename); ?>" href="#<?php echo esc_url($file->filename); ?>"><i class="glyphicon glyphicon-edit"></i></a>
                    <?php else:?>
                    <a target="_blank" href="<?php echo esc_url($file->download_url); ?>" title="<?php echo esc_url($file->filename); ?>" download="<?php echo esc_url($file->filename); ?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo esc_url($file->delete_url); ?>"><i class="glyphicon glyphicon-trash"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <div class="clear"></div>
          </div>
    </form>

</div>
  
  </div>
</div>
<?php endif; ?>
</div>

<?php
    
    $CI =& get_instance();
    
    $lat = $lng = 0;
    
    if(!empty($CI->data['form_object']->lat))
        $lat = $CI->data['form_object']->lat;
    
    if(!empty($CI->data['form_object']->lng))
        $lng = $CI->data['form_object']->lng;
        
    if($lat == 0)
    {
        $lat = config_item('lat');
        $lng = config_item('lng');
    }
?>



<?php 
$custom_js ="";
$custom_js .="
var geocoder_q;
var map_q;
var marker_q;
var timerMap_q;

jQuery(document).ready(function($) {
    
    selio_primary_check();
    
    selio_initMap_q();
    
    $('#is_primary').change(function(){
        selio_primary_check();
    });
    
    $('#input_address').keyup(function (e) {
        clearTimeout(timerMap_q);
        timerMap_q = setTimeout(function () {
            selio_codeAddress();
        }, 2000);
        
    });

    
    selio_loadjQueryUpload();
    
    selio_loadZebra();
    
    function selio_primary_check()
    {
        if($('#is_primary').is(\":checked\"))
        {
            $('div.group_related_id').hide();
        }
        else
        {
            $('div.group_related_id').show();
        }
    }
    
    function selio_codeAddress() {
        var address = document.getElementById('input_address').value;
        geocoder_q.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            document.getElementById(\"input_gps\").value = results[0].geometry.location.lat()+', '+results[0].geometry.location.lng();
          } else {
            ShowStatus.show('"._js(esc_html__('Address not found', 'selio'))."');
          }
        });
    }

    
    function selio_loadZebra()
    {
        $('.files a.iedit').on('click', function (event) {
            new $.Zebra_Dialog('', {
                source: {'iframe': {
                    'src':  '".esc_url(admin_url( 'admin-ajax.php' ))."?action=ci_action&page=files_edit&rel='+$(this).attr('rel'),
                    'height': 700
                }},
                width: 950,
                title:  '"._js(esc_html__('Edit image', 'selio'))."',
                type: false,
                buttons: false
            });
            return false;
        });
    }
    
    function selio_loadjQueryUpload()
    {
        
        $('.zoom-button').on(\"click touchstart\", function()
        {
            var myLinks = new Array();
            var current = $(this).attr('href');
            var curIndex = 0;
            
            $('.files-list .zoom-button').each(function (i) {
                var img_href = $(this).attr('href');
                myLinks[i] = img_href;
                if(current == img_href)
                    curIndex = i;
            });

            options = {index: curIndex}

            blueimp.Gallery(myLinks, options);
            
            return false;
        });
        
        $('form.fileupload').each(function () {
            $(this).fileupload({
            ";
            if(config_item('app_type') != 'demo'):
            $custom_js .="autoUpload: true,";
            endif;
            $custom_js .="
            dataType: 'json',
            // The maximum width of the preview images:
            previewMaxWidth: 160,
            // The maximum height of the preview images:
            previewMaxHeight: 120,
            formData: {
                action: 'ci_action',
                page: 'files_listing',
                repository_id: '".esc_html($repository_id)."'
            },
            uploadTemplateId: null,
            downloadTemplateId: null,
            uploadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    
                    var row = $('<div> </div>');
                    rows = rows.add(row);

                });
                return rows;
            },
            downloadTemplate: function (o) {
                var rows = $();
                $.each(o.files, function (index, file) {
                    var added=false;
                    
                    if (file.error) {
                        ShowStatus.show(file.error);

                    } else {
                        added=true;
                        
                        var row = $('<li class=\"img-rounded template-download fade\">' +
                            '<div class=\"preview\"><span class=\"fade\"></span></div>' +
                            '<div class=\"filename\"><code>'+file.short_name+'</code></div>'+
                            '<div class=\"options-container\">' +
                            (file.zoom_enabled?
                                '<a data-gallery=\"gallery\" class=\"zoom-button btn btn-xs btn-success\" download=\"'+file.name+'\"><i class=\"glyphicon glyphicon-search\"></i></a>'
                                : '<a target=\"_blank\" class=\"btn btn-xs btn-success\" download=\"'+file.name+'\"><i class=\"glyphicon glyphicon-search\"></i></a>') +
                            ' <span class=\"delete\"><button class=\"btn btn-xs btn-danger\" data-type=\"'+file.delete_type+'\" data-url=\"'+file.delete_url+'\"><i class=\"glyphicon glyphicon-trash\"></i></button>' +
                            ' <input type=\"checkbox\" value=\"1\" name=\"delete\"></span>' +
                            '</div>' +
                            (file.error ? '<div class=\"error\"></div>' : '')+'</li>');
                        
                        
                        row.find('.name a').text(file.name);
                        if (file.thumbnail_url) {
                            row.find('.preview').html('<img class=\"img-rounded\" alt=\"'+file.name+'\" data-src=\"'+file.thumbnail_url+'\" src=\"'+file.thumbnail_url+'\">');  
                        }
                        row.find('a').prop('href', file.url);
                        row.find('a').prop('title', file.name);
                        row.find('.delete button')
                            .attr('data-type', file.delete_type)
                            .attr('data-url', file.delete_url);
                    }
                    
                    if(added)
                        rows = rows.add(row);
                });
                
                return rows;
            },
            destroyed: function (e, data) {
            ";
                if(config_item('app_type') != 'demo'):
                $custom_js .="
                if(data.success)
                {

                }
                else
                {
                    ShowStatus.show('"._js(esc_html__('Unsuccessful, possible permission problems or file not exists', 'selio'))."');
                }";
                else:
                $custom_js .="
                if(data.success)
                {
                    
                }
                else
                {
                    ShowStatus.show('"._js(esc_html__('Disabled in demo', 'selio'))."');
                }";
                endif;
                $custom_js .="
                return false;
            },";
                
            if(config_item('app_type') == 'demo'):
            $custom_js .="
            added: function (e, data) {
                
                ShowStatus.show('"._js(esc_html__('Disabled in demo', 'selio'))."');
                return false;
            },";
            endif;
            $custom_js .="
            finished: function (e, data) {
                $('.zoom-button').unbind('click touchstart');
                $('.zoom-button').on(\"click touchstart\", function()
                {
                    var myLinks = new Array();
                    var current = $(this).attr('href');
                    var curIndex = 0;
                    
                    $('.files-list .zoom-button').each(function (i) {
                        var img_href = $(this).attr('href');
                        myLinks[i] = img_href;
                        if(current == img_href)
                            curIndex = i;
                    });
            
                    options = {index: curIndex}
            
                    blueimp.Gallery(myLinks, options);
                    
                    return false;
                });
            },
            dropZone: $(this)
        });
        });       
        
        $(\"ul.files\").each(function (i) {
            $(this).sortable({
                update: selio_saveFilesOrder
            });
            $(this).disableSelection();
        });
    
    }
    
    function selio_filesOrderToArray(container)
    {
        var data = {};

        container.find('li').each(function (i) {
            var filename = $(this).find('.options-container a:first').attr('download');
            data[i+1] = filename;
        });
        
        return data;
    }
    
    function selio_saveFilesOrder( event, ui )
    {
        var filesOrder = selio_filesOrderToArray($(this));
        var repId = $(this).parent().parent().parent().attr('id').substring(13);
        var modelName = $(this).parent().parent().parent().attr('rel');

		$.post('".esc_url(admin_url( 'admin-ajax.php' ))."', 
        {  action: 'ci_action', page: 'files_order',
           'repository_id': repId, 'order': filesOrder }, 
        function(data){
		}, \"json\");
    }

    function selio_initMap_q() {
        
        var myLatlng = {lat: ".esc_html($lat).", lng:".esc_html($lng)."};
        
        if (typeof google === 'object' && typeof google.maps === 'object')
        {
            geocoder_q = new google.maps.Geocoder();
        }
    }

});
";
        
selio_add_into_inline_js( 'selio-custom', $custom_js, true);
?>

