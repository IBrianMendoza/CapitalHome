<div class="agents-list">

<?php if(count($agencies) == 0):  ?>

<div class="alert alert-info" role="alert"><?php echo esc_html__('Not available', 'selio'); ?></div>

<?php else: ?>

<?php foreach($agencies as $key=>$user): ?>

<div class="widget widget-box box-container widget-agent">
    <div class=" media">
        <div class="agent-logo media-left media-middle">
            <a href="<?php echo esc_url(agent_url($user)); ?>">
                <img src="<?php echo esc_url(sw_profile_image($user, 120)); ?>" alt="<?php echo esc_attr($user->display_name); ?>" class="img-circle">
            </a>
        </div>
        <div class="agent-details media-right media-top">
            <a href="<?php echo esc_url(agent_url($user)); ?>" class="agent-name" title="<?php echo esc_attr($user->display_name); ?>"><?php echo esc_html($user->display_name); ?></a>
            <span class="phone" title="<?php echo esc_attr(profile_data($user, 'phone_number'));?>"><?php echo esc_html(profile_data($user, 'phone_number'));?></span>
            <?php if(!empty($user->user_email)):?>
            <a href="<?php echo esc_url("mailto:".$user->user_email); ?>" class="mail text-color-primary"><?php echo esc_html($user->user_email); ?></a>
            <?php endif;?>
            <ul class="clearfix" id="sharing-buttons">
                <?php if(!empty($user->facebook)): ?>
                    <li><a class="facebook"  href="<?php echo esc_url($user->facebook); ?>"><i class="fa fa-facebook"></i></a></li>
                <?php endif; ?>
                <?php if(!empty($user->instagram)): ?>
                    <li><a class="instagram" href="<?php echo esc_url($user->instagram); ?>"><i class="fa fa-instagram"></i></a></li>
                <?php endif; ?>
                <?php if(!empty($user->twitter)): ?>
                    <li><a class="twitter" href="<?php echo esc_url($user->twitter); ?>"><i class="fa fa-twitter"></i></a></li>
                <?php endif; ?>
                <?php if(!empty($user->linkedin)): ?>
                    <li><a class="instagram" href="<?php echo esc_url($user->linkedin); ?>"><i class="fa fa-linkedin"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div><!-- /.agent-card--> 
</div><!-- /. widget-agent -->  

<?php endforeach; ?>

<?php endif; ?>

</div>


