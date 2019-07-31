<div class="bootstrap-wrapper">

<div class="panel panel-default">
  <div class="panel-body">
    
    <?php if(sw_settings('register_page') != NULL): ?>
        <div class="alert alert-info alert-dismissible"><a href="<?php echo get_permalink(sw_settings('register_page')); ?>"><?php echo __('If you are already registered, please first login here', 'sw_win'); ?></a></div>
    <?php endif; ?>
    
    <?php if(!allow_submit_listing()):?>
    
            <div class="alert alert-info alert-dismissible"><?php echo __('Your account don\'t have permission to add listing, you can logout and add listing again', 'sw_win'); ?></div>

    <?php else: ?>
    
    <?php _form_messages(__('Thanks on submission and check your email for all details', 'sw_win')); ?>
    
    <?php 
        if(sw_default_language() != sw_current_language())
        {
            echo '<div class="alert alert-info alert-dismissible">'.__('On quick submission you should populate fields in default website langauge', 'sw_win').': '.sw_get_language_name(sw_default_language()).'</div>';
        }
    ?>
    
    <form action="<?php echo get_permalink(sw_settings('quick_submission')); ?>" class="form-horizontal" method="post">
    
    <div class="row">
    <div class="col-xs-12 col-sm-12">
    
        <?php if(!is_user_logged_in()): ?>
        <div class="form-group <?php _has_error('email'); ?> IS-INPUTBOX row">
            <label for="input_email" class="col-sm-2 control-label"><?php echo __('Your email', 'sw_win'); ?></label>
            <div class="col-sm-10">
                <input class="form-control" id="email" name="email" type="text" value="<?php echo _fv('form_widget', 'email'); ?>" placeholder="<?php echo __('Your email', 'sw_win'); ?>" />
            </div>
        </div><!-- /.form-group -->
        <?php endif; ?>

      <div class="form-group <?php _has_error('address'); ?> IS-INPUTBOX row">
        <label for="input_address" class="col-sm-2 control-label"><?php echo __('Address','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="address" value="<?php echo _fv('form_object', 'address'); ?>" type="text" id="input_address" class="form-control" placeholder="<?php echo __('Address','sw_win'); ?>"/>
        </div>
      </div>


      <div class="form-group <?php _has_error('gps'); ?> IS-INPUTBOX row hidden">
        <label for="input_gps" class="col-sm-2 control-label"><?php echo __('Gps','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="gps" value="<?php echo _fv('form_object', 'gps'); ?>" type="text" id="input_gps" class="form-control" readonly="" placeholder="<?php echo __('Gps','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="hidden form-group <?php _has_error('date_modified'); ?> IS-INPUTBOX row">
        <label for="input_date_modified" class="col-sm-2 control-label"><?php echo __('Date modified','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="date_modified" value="<?php echo _fv('form_object', 'date_modified'); ?>" type="text" id="input_date_modified" readonly="" class="form-control" placeholder="<?php echo __('Date modified','sw_win'); ?>"/>
        </div>
      </div>
      
      <div class="form-group hidden <?php _has_error('repository_id'); ?> row">
        <label for="input_repository_id" class="col-sm-2 control-label"><?php echo __('Repository','sw_win'); ?></label>
        <div class="col-sm-10">
          <input name="repository_id" value="<?php echo _fv('form_object', 'repository_id'); ?>" type="text" id="input_repository_id" class="form-control" readonly="" placeholder="<?php echo __('Repository','sw_win'); ?>"/>
        </div>
      </div>
      
      <?php if(sw_settings('show_categories')): ?>
      
      <div class="form-group <?php _has_error('category_id'); ?> group_category_id row">
        <label for="input_category_id" class="col-sm-2 control-label"><?php echo __('Category','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_treefield('category_id', 'treefield_m', _fv('form_object', 'category_id'), 'value', sw_current_language_id(), 'field_', false, '-', 1);?>
        </div>
      </div>
      
      <?php endif; ?>
      
      <?php if(sw_settings('show_locations')): ?>
      
      <div class="form-group <?php _has_error('location_id'); ?> group_location_id row">
        <label for="input_location_id" class="col-sm-2 control-label"><?php echo __('Location','sw_win'); ?></label>
        <div class="col-sm-10">
          <?php echo form_treefield('location_id', 'treefield_m', _fv('form_object', 'location_id'), 'value', sw_current_language_id(), 'field_', false, '-', 2);?>
        </div>
      </div>
      
      <?php endif; ?>
      
      </div>
      </div>
      
      <div class="row">
      <div class="col-xs-12 col-sm-12">
      <hr />
    <div>
    
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; 
      
        // show just current language
        if(sw_default_language() != $row['lang_code'])
        {
            continue;
        }
      ?>
        <li role="presentation" class="<?php echo $i==1?'active':''?> hidden"><a href="#lang_<?php echo $key?>" aria-controls="<?php echo $row['lang_code']; ?>" role="tab" data-toggle="tab"><?php echo $row['title']; ?></a></li>
      <?php  endforeach; ?>
      </ul>
        
      <!-- Tab panes -->
      <div class="tab-content">
      
      <?php $i=0;foreach(sw_get_languages() as $key=>$row):$i++; 
      
        // show just current language
        if(sw_default_language() != $row['lang_code'])
        {
            continue;
        }
      ?>
      
      
        <div role="tabpanel" class="tab-pane <?php echo $i==1?'active':''?>" id="lang_<?php echo $key?>">

          <div class="field_slug form-group <?php _has_error('input_slug_'.$key); ?> row hidden">
            <label for="input_slug_<?php echo $key; ?>" class="col-sm-2 control-label"><?php echo __('Slug', 'sw_win'); ?></label>
            <div class="col-sm-10">
              <input name="input_slug_<?php echo $key; ?>" type="text" value="<?php echo _fv('form_object', 'input_slug_'.$key); ?>" class="form-control" id="input_slug_<?php echo $key; ?>" placeholder="<?php echo __('Slug', 'sw_win'); ?>">
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
            <div class="field_<?php echo $field->idfield; ?>">
            <hr />
            <h4><?php echo $field->field_name?></h4>
            <hr />
            </div>
            <?php elseif($field->type == 'INPUTBOX' || $field->type == 'DECIMAL' || $field->type == 'INTEGER'): ?>
            
            <?php
            
            $field_lang = $this->field_m->get_field_data($field->idfield, $key);
            
            $presuf='';
            if(!empty($field_lang))
                $presuf = $field_lang->prefix.$field_lang->suffix;
            ?>
            
          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> row">
            <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <?php if(empty($presuf)): ?>
            <div class="col-sm-10">
              <input name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" value="<?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?>" class="form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>" placeholder="<?php echo $field->field_name; ?>">
            </div>
            <?php else: ?>
                <div class="col-sm-7">
                  <input name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" value="<?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?>" class="form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>" placeholder="<?php echo $field->field_name; ?>">
                </div>
                <div class="col-sm-3">
                    <?php echo $presuf; ?>
                </div>
            <?php endif; ?>
          </div>
          
            <?php elseif($field->type == 'TEXTAREA'): ?>
            
          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> row">
            <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
              <textarea name="input_<?php echo $field->idfield.'_'.$key; ?>" type="text" class="form-control" id="input_<?php echo $field->idfield.'_'.$key; ?>"><?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key); ?></textarea>
            </div>
          </div>
          
            <?php elseif($field->type == 'DROPDOWN' || $field->type == 'DROPDOWN_MULTIPLE'): ?>
            
            <?php
            
                $field_lang = $this->field_m->get_field_data($field->idfield, $key);
                $values_available = explode(',', $field_lang->values);
                $values_available = array_combine($values_available, $values_available);
            
            ?>

          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> row">
            <label for="input_<?php echo $field->idfield.'_'.$key; ?>" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
              <?php echo form_dropdown('input_'.$field->idfield.'_'.$key, $values_available, _fv('form_object', 'input_'.$field->idfield.'_'.$key), 'class="form-control"')?>
            </div>
          </div>
          
            <?php elseif($field->type == 'CHECKBOX'): ?>

          <div class="field_<?php echo $field->idfield; ?> form-group <?php _has_error('input_'.$field->idfield.'_'.$key); ?> row">
            <label for="inputIsLocked" class="col-sm-2 control-label"><?php echo $required.$field->field_name; ?></label>
            <div class="col-sm-10">
              <input name="<?php echo 'input_'.$field->idfield.'_'.$key; ?>" value="1" type="checkbox" <?php echo _fv('form_object', 'input_'.$field->idfield.'_'.$key, 'CHECKBOX'); ?>/>
            </div>
          </div>
      
            <?php else: ?>
                <?php dump($field); ?>
            <?php endif; ?>
            
            <?php endforeach; ?>
        
        </div>
        <?php endforeach; ?>
      </div>
    
    </div>
      </div>
      </div>
        <?php if (sw_settings('terms_link')): ?>
        <div class="form-group  row">
          <div class="col-md-offset-2 col-sm-10">
                <input name="option_agree_terms" value="true" type="checkbox" id="inputOption_terms"/>
                <label for="inputOption_terms" class="control-label"><a target="_blank" href="<?php echo sw_settings('terms_link'); ?>"><?php echo esc_html__('I Agree To The Terms & Conditions', 'nexos'); ?></a></label>
          </div>
        </div>
        <?php endif; ?>
      <?php if(function_exists('sw_win_load_ci_function_rankpackages')): ?>
      <div class="row">
      <div class="col-xs-12 col-sm-12">
        
        <hr />
        <h4><?php echo __('Rank package', 'sw_win'); ?></h4>
        <hr />
        
        <p>
        <?php echo __('Purchase higher listing rank and sell faster', 'sw_win'); ?>
        </p>
        <table class="table table-striped">
        <thead>
            <tr>
                <th style="width: 20px;"></th>
                <th><?php echo __('Package name', 'sw_win');?></th>
                <th style="text-align:center;"><?php echo __('Days expire', 'sw_win');?></th>
                <th style="text-align:right;"><?php echo __('Price', 'sw_win');?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($rank_packages as $key=>$rank_package): 
            $selected = $key == 0 || (isset($_POST['packagerank']) && $_POST['packagerank'] == $rank_package->idpackagerank);
        ?>
            <tr>
                <td><?php echo form_radio('packagerank', $rank_package->idpackagerank, $selected, 'style="display:inline;width:auto;"'); ?></td>
                <td><strong><?php echo $rank_package->package_name; ?></strong></td>
                <td style="text-align:center;"><?php echo $rank_package->package_days==0?'-':$rank_package->package_days; ?></td>
                <td style="text-align:right;"><?php echo $rank_package->package_price.' '.sw_settings('default_currency'); ?></td>
            </tr>
        <?php //dump($rank_package); ?>
        <?php endforeach; ?>
        </tbody>
        </table>
      </div>
      </div>
      <?php endif; ?>
      
      <input class="hidden" id="widget_id" name="widget_id" type="text" value="quick_submission" />
      <?php if(!is_user_logged_in()): ?>
        <hr />
          <div class="form-group row">
            <label for="inputcaptcha" class="col-sm-2 control-label"><?php echo __('Recaptcha', 'sw_win'); ?></label>
            <div class="col-sm-10">
              <?php echo _recaptcha(false); ?>
            </div>
          </div>
      <?php endif; ?>

      <hr />
      
      <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary"><?php echo __('Save', 'sw_win'); ?></button>
        </div>
      </div>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php if(allow_submit_listing()):?>
<div class="panel panel-default">
  <div class="panel-heading">
<hr />
<h4><?php echo __('Photo and other files','sw_win'); ?></h4>
<hr />
  </div>
  <div class="panel-body">
<div class="upload-files-widget" id="upload-files-<?php echo $repository_id; ?>" rel="listing_m">
    <!-- The file upload form used as target for the file upload widget -->
    <form class="fileupload" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="#<?php echo admin_url("admin.php?page=listing_manage"); ?>" /></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="fileupload-buttonbar">
            <div class="span7 col-md-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="icon-plus icon-white"></i>
                    <span><?php echo __('Add files...', 'sw_win')?></span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="button" class="btn btn-danger delete">
                    <i class="icon-trash icon-white"></i>
                    <span><?php echo __('Delete selected', 'sw_win')?></span>
                </button>
                <input type="checkbox" class="toggle" />
            </div>
            <!-- The global progress information -->
            <div class="span5 col-md-5 fileupload-progress fade">
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
        <br style="clear:both;"/>
        <!-- The table listing the files available for upload/download -->
        <!--<table role="presentation" class="table table-striped">
        <tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery">-->

          <div role="presentation" class="fieldset-content">
              
            <ul class="files files-list" data-toggle="modal-gallery" data-target="#modal-gallery">      
<?php foreach($this->file_m->get_repository($repository_id) as $file ):?>
<?php sw_add_file_tags($file); ?>
            <li class="img-rounded template-download fade in">
                <div class="preview">
                    <img class="img-rounded" alt="<?php echo $file->filename; ?>" data-src="<?php echo $file->thumbnail_url; ?>" src="<?php echo $file->thumbnail_url; ?>">
                </div>
                <div class="filename">
                    <code><?php echo character_hard_limiter($file->filename, 20)?></code>
                </div>
                <div class="options-container">
                    <?php if($file->zoom_enabled):?>
                    <a data-gallery="gallery" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="zoom-button btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>                  
                    <a class="btn btn-xs btn-info iedit visible-inline-lg" rel="<?php echo $file->filename?>" href="#<?php echo $file->filename?>"><i class="glyphicon glyphicon-edit"></i></a>
                    <?php else:?>
                    <a target="_blank" href="<?php echo $file->download_url?>" title="<?php echo $file->filename?>" download="<?php echo $file->filename?>" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-search"></i></a>
                    <?php endif;?>
                    <span class="delete">
                        <button class="btn btn-xs btn-danger" data-type="POST" data-url="<?php echo $file->delete_url?>"><i class="glyphicon glyphicon-trash"></i></button>
                        <input type="checkbox" value="1" name="delete">
                    </span>
                </div>
            </li>
<?php endforeach;?>
            </ul>
            <br style="clear:both;"/>
          </div>
    </form>

</div>
  
  </div>
</div>
<?php endif; ?>
</div>

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">&lsaquo;</a>
    <a class="next">&rsaquo;</a>
    <a class="close">&times;</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
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

<script>

var geocoder_q;
var map_q;
var marker_q;
var timerMap_q;

jQuery(document).ready(function($) {
    
    primary_check();
    
    initMap_q();
    
    $('#is_primary').change(function(){
        primary_check();
    });
    
//    $('#input_address').change(function(){
//       codeAddress(); 
//    });
    
    $('#input_address').keyup(function (e) {
        clearTimeout(timerMap_q);
        timerMap_q = setTimeout(function () {
            codeAddress();
        }, 2000);
        
    });

    
    loadjQueryUpload();
    
    loadZebra();
    
    function primary_check()
    {
        if($('#is_primary').is(":checked"))
        {
            $('div.group_related_id').hide();
        }
        else
        {
            $('div.group_related_id').show();
        }
    }
    
    function codeAddress() {
        var address = document.getElementById('input_address').value;
        geocoder_q.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            document.getElementById("input_gps").value = results[0].geometry.location.lat()+', '+results[0].geometry.location.lng();
          } else {
            ShowStatus.show('<?php echo_js(__('Address not found', 'sw_win'));?>');
            //alert('<?php echo_js(__('Address not found', 'sw_win')); ?>');
          }
        });
    }

    
    function loadZebra()
    {
        $('.files a.iedit').click(function (event) {
            new $.Zebra_Dialog('', {
                source: {'iframe': {
                    'src':  '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=ci_action&page=files_edit&rel='+$(this).attr('rel'),
                    'height': 700
                }},
                width: 950,
                title:  '<?php echo_js(__('Edit image', 'sw_win')); ?>',
                type: false,
                buttons: false
            });
            return false;
        });
    }
    
    function loadjQueryUpload()
    {
        
        $('.zoom-button').bind("click touchstart", function()
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
            <?php if(config_item('app_type') != 'demo'):?>
            autoUpload: true,
            <?php endif;?>
            dataType: 'json',
            // The maximum width of the preview images:
            previewMaxWidth: 160,
            // The maximum height of the preview images:
            previewMaxHeight: 120,
            formData: {
                action: 'ci_action',
                page: 'files_listing',
                repository_id: '<?php echo $repository_id; ?>'
            },
            uploadTemplateId: null,
            downloadTemplateId: null,
            uploadTemplate: function (o) {
                var rows = $();
                //return rows;
                $.each(o.files, function (index, file) {
                    /*
                    var row = $('<li class="img-rounded template-upload">' +
                        '<div class="preview"><span class="fade"></span></div>' +
                        '<div class="filename"><code>'+file.name+'</code></div>'+
                        '<div class="options-container">' +
                        '<span class="cancel"><button  class="btn btn-xs btn-warning"><i class="icon-ban-circle icon-white"></i></button></span></div>' +
                        (file.error ? '<div class="error"></div>' :
                                '<div class="progress">' +
                                    '<div class="bar" style="width:0%;"></div></div></div>'
                        )+'</li>');
                    row.find('.name').text(file.name);
                    row.find('.size').text(o.formatFileSize(file.size));
                    */
                    
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
                        
                        var row = $('<li class="img-rounded template-download fade">' +
                            '<div class="preview"><span class="fade"></span></div>' +
                            '<div class="filename"><code>'+file.short_name+'</code></div>'+
                            '<div class="options-container">' +
                            (file.zoom_enabled?
                                '<a data-gallery="gallery" class="zoom-button btn btn-xs btn-success" download="'+file.name+'"><i class="glyphicon glyphicon-search"></i></a>'
                                : '<a target="_blank" class="btn btn-xs btn-success" download="'+file.name+'"><i class="glyphicon glyphicon-search"></i></a>') +
                            ' <span class="delete"><button class="btn btn-xs btn-danger" data-type="'+file.delete_type+'" data-url="'+file.delete_url+'"><i class="glyphicon glyphicon-trash"></i></button>' +
                            ' <input type="checkbox" value="1" name="delete"></span>' +
                            '</div>' +
                            (file.error ? '<div class="error"></div>' : '')+'</li>');
                        
                        
                        row.find('.name a').text(file.name);
                        if (file.thumbnail_url) {
                            row.find('.preview').html('<img class="img-rounded" alt="'+file.name+'" data-src="'+file.thumbnail_url+'" src="'+file.thumbnail_url+'">');  
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
                <?php if(config_item('app_type') != 'demo'):?>
                if(data.success)
                {

                }
                else
                {
                    ShowStatus.show('<?php echo_js(__('Unsuccessful, possible permission problems or file not exists', 'sw_win')); ?>');
                }
                <?php else: ?>
                if(data.success)
                {
                    
                }
                else
                {
                    ShowStatus.show('<?php echo_js(__('Disabled in demo', 'sw_win')); ?>');
                }
                <?php endif;?>
                return false;
            },
            <?php if(config_item('app_type') == 'demo'):?>
            added: function (e, data) {
                
                ShowStatus.show('<?php echo_js(__('Disabled in demo', 'sw_win')); ?>');
                return false;
            },
            <?php endif;?>
            finished: function (e, data) {
                $('.zoom-button').unbind('click touchstart');
                $('.zoom-button').bind("click touchstart", function()
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
        
        $("ul.files").each(function (i) {
            $(this).sortable({
                update: saveFilesOrder
            });
            $(this).disableSelection();
        });
    
    }
    
    function filesOrderToArray(container)
    {
        var data = {};

        container.find('li').each(function (i) {
            var filename = $(this).find('.options-container a:first').attr('download');
            data[i+1] = filename;
        });
        
        return data;
    }
    
    function saveFilesOrder( event, ui )
    {
        var filesOrder = filesOrderToArray($(this));
        var repId = $(this).parent().parent().parent().attr('id').substring(13);
        var modelName = $(this).parent().parent().parent().attr('rel');

        //$.fn.startLoading();
		$.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', 
        {  action: 'ci_action', page: 'files_order',
           'repository_id': repId, 'order': filesOrder }, 
        function(data){
            //$.fn.endLoading();
		}, "json");
    }

    function initMap_q() {
        
        var myLatlng = {lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?>};
        
        if (typeof google === 'object' && typeof google.maps === 'object')
        {
            geocoder_q = new google.maps.Geocoder();
        }
        
    
    }

});



</script>

<style>

    #map_q {
        height: 300px;
    }
    
    .bootstrap-wrapper .alert.non-translatable {
        padding: 7px 12px;
        margin-bottom: 0px;
    }

    .blueimp-gallery {
        z-index: 99999;
    }
    
    hr {
        margin:1.75em 0 1.75em 0;
    }
    
    .panel-body h4, .bootstrap-wrapper h4
    {
        margin:1.5em 0 1.5em 0;
    }
    
    .bootstrap-wrapper .upload-files-widget .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        
        font-family: Montserrat, "Helvetica Neue", sans-serif;
        vertical-align: top;
        
    }
    
    .bootstrap-wrapper .upload-files-widget .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
        text-transform: none;
        float:left;
    }
    
    .bootstrap-wrapper .upload-files-widget .btn-success {
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;
    }
    
    .ci.sw_widget .bootstrap-wrapper .upload-files-widget .btn-xs, 
    .ci.sw_widget .bootstrap-wrapper .upload-files-widget .btn-group-xs > .btn {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
        height: auto;
    }
    
    .ci.sw_widget .bootstrap-wrapper .upload-files-widget .btn-xs i{
        font-size: 13px;
    }
    
    .sw_widget .fileupload-buttonbar *
    {
        font-size: 14px;
    }
    
    .files-list .btn-info.iedit.visible-inline-lg
    {
        display:none !important;
    }
    
    .ci.sw_widget.sw_wrap .upload-files-widget input[type="checkbox"] {
        float: left;
        width: auto;
        margin: 4px 4px 0px 4px;
    }
    
    .bootstrap-wrapper .upload-files-widget code {
        padding: 2px 4px;
        font-size: 90%;
        color: #c7254e;
        background-color: #f9f2f4;
        border-radius: 4px;
        
        display:inline-block;
        white-space: nowrap;
        overflow:hidden !important;
        text-overflow: ellipsis;
    }
    
    .bootstrap-wrapper .upload-files-widget span.delete
    {
        display: inline-block;
    }
    
    ul.files-list
    {
        list-style: none;
    }
    
    ul.files-list li
    {
        overflow: hidden;
        height: 177px;
    }
    
    ul.files-list .filename
    {
        line-height: 1;
    }
    
    div.options-container
    {
        line-height: 1;
        outline-style: none;
        outline-width: 0px;
        font-size:13px;
    }
    
    .bar {
        background-color: #e8e8e8;
        border-right-color: #99d;
    }
    
    .progress-extended
    {
        overflow: hidden;
    }
    
    .fileinput-button input
    {
        transform:none;
    }
    
    .bootstrap-wrapper .progress {
        height: 20px;
        margin-bottom: 0px;
        overflow: hidden;
        background-color: #f5f5f5;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .fileupload-progress
    {
        height: 40px;
        overflow: hidden;
    }
    
    .bootstrap-wrapper .has-error .help-block, .bootstrap-wrapper .has-error .control-label, .bootstrap-wrapper .has-error .radio, .bootstrap-wrapper .has-error .checkbox, .bootstrap-wrapper .has-error .radio-inline, .bootstrap-wrapper .has-error .checkbox-inline, .bootstrap-wrapper .has-error.radio label, .bootstrap-wrapper .has-error.checkbox label, .bootstrap-wrapper .has-error.radio-inline label, .bootstrap-wrapper .has-error.checkbox-inline label {
        color: #a94442;
    }

    .ci.sw_widget.sw_wrap .bootstrap-wrapper .has-error input.form-control {
        border-color: #a94442;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }
    
    .files .preview *
    {
        width:auto;
    }
    
        
    <?php if(function_exists('sw_settings') && sw_settings('quicksubmission_gallery_on_top') == 1):?>
            .bootstrap-wrapper {
                display: -webkit-flex;
                display: flex;
                -webkit-flex-direction: column-reverse;
                flex-direction: column-reverse;
            }
    <?php endif ?>
</style>

