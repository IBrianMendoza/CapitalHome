
<div class="agent-profile">
    <div class="agent-img">
        <img src="<?php echo esc_url(sw_profile_image($user, 607)); ?>" alt="<?php echo esc_attr(selio_ch($user->display_name, '-')); ?>">
    </div><!--agent-img end-->
    <div class="agent-info">
        <h3><?php echo esc_html(selio_ch($user->display_name, '-')); ?></h3>
        <h4><?php echo esc_attr(profile_data($user, 'position_title')); ?></h4>

        <?php if (!empty($user_meta['description'][0])): ?>
        <p class="profile-description"><?php echo esc_html(selio_ch($user_meta['description'][0], '-')); ?></p>
        <?php endif; ?>

        <ul class="cont-links">
            <li><span><i class="la la-phone"></i><?php echo esc_attr(profile_data($user, 'phone_number')); ?></span></li>
            <li><a href="<?php echo esc_url('mailto:' . _ch($user->user_email, '-')); ?>" title="<?php echo esc_attr(selio_ch($user->user_email, '-')); ?>"><i class="la la-envelope"></i><?php echo esc_html(selio_ch($user->user_email, '-')); ?></a></li>
        </ul>

        <?php if (shortcode_exists('sw_win_selio_share_userprofile')): ?>
            <?php echo do_shortcode('[sw_win_selio_share_userprofile user_id="'.esc_attr($user->ID).'"]'); ?>
        <?php endif; ?>
    </div><!--agent-info end-->
</div><!--agent-profile end-->


