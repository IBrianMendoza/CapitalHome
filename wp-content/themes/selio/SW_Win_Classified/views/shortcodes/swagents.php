<div class="row clerfix">

<?php foreach($agents as $key=>$user_details): 
      $user = $user_details->data;
?>

<div class="col-sm-6">
    <div class="agent-card complete_box">
        <div class="agent-logo media-left media-top">
            <a href="<?php echo esc_url(agent_url($user)); ?>" class="img-circle-cover">
                <img src="<?php echo esc_url(sw_profile_image($user, 120)); ?>" alt="..." class="img-circle">
            </a>
        </div>
        <div class="agent-details media-right media-top">
            <div class="header">
                <a href="<?php echo esc_url(agent_url($user)); ?>" class="agent-name"><?php echo esc_html($user->display_name); ?> </a>
                <span class="subtitle"  title="<?php echo esc_attr(profile_data($user, 'position_title'));?>"><?php echo esc_html(profile_data($user, 'position_title')); ?></span>
            </div>
            <span class="description two-lines"><?php echo esc_html(wp_trim_words(get_the_author_meta( "user_description", $user_details->ID ), 10, '...' ));?></span>
            <span class=""><i class="fa fa-envelope-o"></i><a href="<?php echo esc_url('mailto:'.$user->user_email); ?>" class="mail" title="<?php echo esc_attr($user->user_email); ?>" ><?php echo esc_html($user->user_email); ?></a></span>
        </div>
        <a href="<?php echo esc_url(agent_url($user)); ?>" class="complete_box_link"></a>
    </div><!-- /.agent-card--> 
</div>
<?php endforeach; ?>
</div>