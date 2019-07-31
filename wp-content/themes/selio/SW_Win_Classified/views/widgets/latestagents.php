<div class="agents-list">

<?php if(count($agents) == 0): ?>

<div class="alert alert-info" role="alert"><?php echo esc_html__('Not available', 'selio'); ?></div>

<?php else: ?>

<?php foreach($agents as $key=>$user_details): 
      $user = $user_details->data;
?>

<div class="widget widget-box box-container widget-agent">
    <div class=" media">
        <div class="agent-logo media-left media-middle">
            <a href="<?php echo esc_url(agent_url($user)); ?>">
                <img src="<?php echo esc_url(sw_profile_image($user, 120)); ?>" alt="<?php echo esc_attr($user->display_name); ?>" class="img-circle">
            </a>
        </div>
        <div class="agent-details media-right media-top">
            <a href="<?php echo esc_url(agent_url($user)); ?>" class="agent-name"><?php echo esc_html($user->display_name); ?></a>
            <span class="phone" title="<?php echo esc_attr(profile_data($user, 'phone_number'));?>"><?php echo esc_html(profile_data($user, 'phone_number'));?></span>
            <a href="<?php echo esc_url('mailto:'.$user->user_email); ?>" title="<?php echo esc_attr($user->user_email); ?>" class="mail text-color-primary"><?php echo esc_html($user->user_email); ?></a>
                            </div>
    </div><!-- /.agent-card--> 
</div><!-- /. widget-agent -->  

<?php endforeach; ?>

<?php endif; ?>

</div>






