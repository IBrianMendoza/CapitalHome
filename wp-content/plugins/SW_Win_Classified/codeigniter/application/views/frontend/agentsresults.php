<div id="results-agents" >

<span id="results_top"></span>

<?php if($agents_count == 0): ?>

<div class="row">
    <div class="col-xs-12">
    <div class="alert alert-info" role="alert"><?php echo __('Agents not found', 'sw_win'); ?></div>
    </div>
</div>

<?php else: ?>

<div>

<?php foreach($agents as $key=>$user_details): 
      $user = $user_details->data;
?>

<div class="column-sep col-md-4 col-sm-6">
<div class="agent-item">

    <div class="col-xs-4">
        <div class="image-box">
            <img src="<?php echo sw_profile_image($user, 100); ?>" alt="" class="image" />
            <a href="<?php echo agent_url($user); ?>" class="property-card-hover">
                <img src="<?php echo plugins_url( SW_WIN_SLUG.'/assets' );?>/img/plus.png" alt="" class="center-icon" />
            </a>
        </div>
    </div>
    
    <div class="col-xs-8">
    <div class="sw-smallbox">
        <div class="sw-smallbox-title"><a href="<?php echo agent_url($user); ?>"><?php echo $user->display_name; ?></a></div>
        <div class="sw-smallbox-address"><?php echo $user->user_email; ?></div>
        <div class="sw-smallbox-price"><?php echo _ch($user->user_url, '-'); ?></div>
    </div>
    </div>

</div>
</div>

<?php endforeach; ?>
</div>

<br style="clear: both;" />

<?php echo $pagination_links; ?>


<?php endif; ?>

</div>