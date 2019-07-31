<?php
$language_id = sw_current_language_id();

$export_files_exists = false;
$export_files_type = array('pdf',"application/pdf");
if(isset($images) && !empty($images))
foreach($images as $image)
{
    if(in_array($image->filetype, $export_files_type) !== FALSE ) {
         $export_files_exists = $image->filename;
    }
}
?>
<?php if($export_files_exists):?>
    <a href="<?php echo esc_url(sw_win_upload_dir().'/files/'.$export_files_exists); ?>" onclick="" class="btn btn-primary color-primary btn-property btn-print sw-btn-export" > <?php echo __('PDF Export','sw_win');?></a>
<?php else:?>
    <a href="<?php echo get_site_url();?>?export=pdf&listing_id=<?php echo $listing->idlisting;?>&lang_id=<?php echo $language_id;?>" onclick="" class="btn btn-primary color-primary btn-property btn-print sw-btn-export" > <?php echo __('PDF Export','sw_win');?></a>
<?php endif;?>










