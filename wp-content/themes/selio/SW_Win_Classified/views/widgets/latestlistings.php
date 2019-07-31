<div class="widget-posts">

<?php if(count($listings) == 0): ?>

<div class="alert alert-info" role="alert"><?php echo esc_html__('Not available', 'selio'); ?></div>

<?php else: ?>
<ul>
<?php foreach($listings as $key=>$listing): ?>
    <li>
        <div class="wd-posts">
            <div class="ps-img">
                <a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>">
                    <img src="<?php echo esc_url(_show_img($listing->image_filename, '224x178', true)); ?>" alt="<?php echo esc_attr(_field($listing, 10)); ?>">
                </a>
            </div><!--ps-img end-->
            <div class="ps-info">
                <h3><a href="<?php echo esc_url(listing_url($listing)); ?>" title="<?php echo esc_attr(_field($listing, 10)); ?>"><?php echo esc_html(esc_html(_field($listing, 10))); ?></a></h3>
                <strong>
                    <?php // @codingStandardsIgnoreStart ?>
                    <?php if(!empty(_field($listing, 37)) && _field($listing, 37) !='-'):?>
                    <?php echo esc_html(_field($listing, 37)); ?>
                    <?php else:?>
                    <?php echo esc_html(_field($listing, 36)); ?>
                    <?php endif;?>
                    <?php // @codingStandardsIgnoreEnd ?>
                </strong>
                <span><i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></span>
            </div><!--ps-info end-->
        </div><!--wd-posts end-->
    </li>
<?php endforeach; ?>
</ul>

<?php endif; ?>
    
</div>













