<div class="agents-list">

<h3 class="widget-title"><?php echo esc_html('Contact Listing Agent','selio');?></h3>

<?php if(count($agents) == 0): ?>

<div class="alert alert-info" role="alert"><?php echo esc_html__('Not available', 'selio'); ?></div>

<?php else: ?>

<?php foreach($agents as $key=>$user): ?>

<div class="contct-info">
    <a href="<?php echo esc_url(agent_url($user)); ?>">
        <img src="<?php echo esc_url(sw_profile_image($user, 81)); ?>" alt="<?php echo esc_attr($user->display_name); ?>">
    </a>
    <div class="contct-nf">
        <h3><a href="<?php echo esc_url(agent_url($user)); ?>"><?php echo esc_html($user->display_name); ?></a></h3>
        <h4><?php echo esc_attr(profile_data($user, 'position_title'));?></h4>
        <span><i class="la la-phone"></i>
            <a href="tell://<?php echo esc_attr(profile_data($user, 'phone_number'));?>">
                <?php
                $phone = profile_data($user, 'phone_number');
                if(stripos($phone, '-') === FALSE && stripos($phone, ' ') === FALSE) {
                    $phone = sprintf("%s %s-%s-%s-%s",
                                    substr($phone, 0, 3),
                                    substr($phone, 3, 3),
                                    substr($phone, 6, 3),
                                    substr($phone, 9, 2),
                                    substr($phone, 11));
                   echo esc_html($phone);
                } else {
                   echo esc_html($phone);
                }
                ?>
                
            </a>
        </span>
    </div>
</div><!--contct-info end-->

<?php endforeach; ?>

<?php endif; ?>

</div>
