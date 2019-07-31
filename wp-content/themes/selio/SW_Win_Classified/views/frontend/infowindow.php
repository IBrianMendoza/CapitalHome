<div class="infobox map-box">
    <a href="<?php echo esc_url(listing_url($listing)); ?>" class="listing-img-container">
        <div class="infoBox-close"><i class="fa fa-times"></i>
        </div><img src="<?php echo esc_url(_show_img($listing->image_filename, '575x700', true)); ?>" alt="<?php echo esc_attr(_field($listing, 10)); ?>">
        <div class="rate-info">
            <h5>
                <?php if(_field($listing, 37) && _field($listing, 37) !='-'):?>
                <?php echo esc_html(_field($listing, 37)); ?>
                <?php else:?>
                <?php echo esc_html(_field($listing, 36)); ?>
                <?php endif;?>
            </h5> 
            <span class="purpose-<?php echo esc_attr(url_title(_field($listing, 4), '-', TRUE)); ?>">
                <?php echo esc_html(esc_html(_field($listing, 4))); ?>
            </span> 
        </div>
        <div class="listing-item-content">
            <h3><?php echo esc_html(_field($listing, 10)); ?></h3>
            <span><i class="la la-map-marker"></i><?php echo esc_html(_field($listing, 'address')); ?></span>
        </div>
    </a>
</div>