<div class="row">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo esc_url(sw_profile_image($user, 200)); ?>" alt="..." class="image" />
        </div>
    </div>

    <div class="col-xs-8">
        <?php if(!empty($user_meta['description'][0])): ?>
        <p><?php echo esc_html(selio_ch($user_meta['description'][0], '-')); ?></p>
        <?php endif; ?>
        <p><?php echo esc_html__('Name', 'selio').': '.esc_html(selio_ch($user->display_name, '-')); ?></p>
        <p><?php echo esc_html__('Email', 'selio').': <a href="'.esc_url(selio_ch($user->user_email, '#')).'">'.esc_html(selio_ch($user->user_email, '-')).'</a>'; ?></p>
        
        <?php if(!empty($user->user_url)): ?>
        <p><?php echo esc_html__('Website', 'selio').': <a href="'.esc_url(selio_ch($user->user_url, '#')).'">'.esc_html(selio_ch($user->user_url, '-')).'</a>'; ?></p>
        <?php endif; ?>
    </div>

</div>