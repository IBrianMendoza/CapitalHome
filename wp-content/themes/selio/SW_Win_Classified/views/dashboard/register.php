<?php
$custom_js ='';
$custom_js .= "
        jQuery(document).ready(function($){
            if(window.location.hash && window.location.hash.substr(1)=='sw_register') {
                $('.sign-up-form').addClass('active show')
                $('.sign-up').addClass('active')
            } else {
                $('.log-in-form').addClass('active show')
            }
            
            $('.signin-op').on('click',function(e){
                e.preventDefault();
                $('.log-in').click();
            })
            
            $('.create-op').on('click',function(e){
                e.preventDefault();
                $('.sign-up').click();
            })
        })
        ";
selio_add_into_inline_js( 'selio-custom', $custom_js, true);
?>



<?php if (sw_is_logged_user()): ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success mb50" role="alert"><?php echo esc_html__('You are already logged in', 'selio'); ?>, <a href="<?php echo esc_url(admin_url("")); ?>"><?php echo esc_html__('Open dashboard', 'selio'); ?></a></div>
            </div>
        </div>
    </div>
    <?php get_sidebar('bottom-selio'); ?>
<?php else: ?>

<ul class="nav nav-tabs d-none sw-sign-form-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link log-in" href="#log-in-form" role="tab" data-toggle="tab"><?php echo esc_html__('Log in', 'selio'); ?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link sign-up" href="#sign-up-form" role="tab" data-toggle="tab"><?php echo esc_html__('Sign Up', 'selio'); ?></a>
  </li>
</ul>

    <div class="sign-form-wr">
        <div class="sign-form-inner tab-content">
                <!-- Log In -->
                <div class="form-wr log-in-form tab-pane fade"  role="tabpanel" id="log-in-form">
                    <h3><?php echo esc_html__('Sign In to your Account', 'selio'); ?></h3>
                    <div class="form-wr-content">
                        <form method="post" action="#sw_login" >
                            <?php _form_messages(esc_html__('Login successfuly', 'selio'), esc_html__('Wrong credentials', 'selio'), 'login'); ?>
                            <?php if(function_exists('config_item') && config_item('app_type') == 'demo'): ?>
                                <div class="alert alert-success m0" role="alert">
                                    <b><?php echo esc_html__('Demo login details for Admin', 'selio'); ?>:</b><br />
                                    <?php echo esc_html__('Username', 'selio'); ?>: <?php echo esc_html('admin'); ?><br />
                                    <?php echo esc_html__('Password', 'selio'); ?>:  <?php echo esc_html('admin'); ?><br /><br />
                                    <b> <?php echo esc_html__('Demo login details for Agent', 'selio'); ?>:</b><br />
                                    <?php echo esc_html__('Username', 'selio'); ?>:  <?php echo esc_html('agent'); ?><br />
                                    <?php echo esc_html__('Password', 'selio'); ?>:  <?php echo esc_html('agent'); ?>
                                </div>
                            <?php endif; ?>
                             <div class="form-field">
                                <input type="text" name="username" placeholder="<?php echo esc_attr__('Your Name', 'selio'); ?>" class="login" required="">
                            </div>
                             <div class="form-field">
                                <input type="password" name="password" placeholder="<?php echo esc_attr__('Password', 'selio'); ?>" class="password" required="">
                            </div>
                            <div class="form-cp">
                                <div class="form-field">
                                    <div class="input-field">
                                        <input type="checkbox" name="remember" id="remember">
                                        <label for="remember">
                                            <span></span>
                                            <small><?php echo esc_html__('Remember me', 'selio'); ?></small>
                                        </label>
                                    </div>
                                </div>
                                <a href="#" class="forgot-password create-op" title="<?php echo esc_attr__('Create?', 'selio'); ?>"><?php echo esc_html__('Create?', 'selio'); ?></a> <span class="or"> / </span>
                                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="forgot-password" title="<?php echo esc_attr__('Forgot Password?', 'selio'); ?>"><?php echo esc_html__('Forgot Password?', 'selio'); ?></a>
                            </div><!--form-cp end-->
                            <button type="submit" class="btn2"><?php echo esc_attr__('Sign In', 'selio'); ?></button>
                            <input class="hidden" id="widget_id_login_2" name="widget_id" type="text" value="login" />
                        </form>
                        <?php if (selio_plugin_call::sw_settings('facebook_login_enabled') == '1' && selio_plugin_call::sw_settings('facebook_app_id') != ''): ?>
                            <a href="<?php echo esc_url($facebook_login_url); ?>" class="fb-btn"><i class="fa fa-facebook" aria-hidden="true"></i><?php echo esc_html__('Sign in with facebook', 'selio'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- End Log In -->
                <!-- Sign In -->
                <div class="form-wr sign-up-form tab-pane fade"  role="tabpanel" id="sign-up-form">
                    <h3><?php echo esc_html__('Register', 'selio'); ?></h3>
                    <div class="form-wr-content">
                        <form method="post" action="#sw_register" >
                            <?php _form_messages(esc_html__('Register successfuly, you can login now', 'selio'), NULL, 'register'); ?>
                             <div class="form-field">
                                <div class="drop-menu">
                                    <div class="select">
                                        <span><?php echo esc_html__('Select type', 'selio');?></span>
                                        <i class="la la-caret-down"></i>
                                    </div>
                                    <input type="hidden" name="account_type" id="account_type" value="<?php echo esc_attr(_fv('form_widget', 'account_type'));?>">
                                    <ul class="dropeddown">
                                         <?php foreach (array_merge(config_item('account_types'), array('' => esc_html__('Select type', 'selio'))) as $key => $value):?>
                                            <li data-value="<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                            </div>
                            
                             <div class="form-field">
                                <input class="" id="email" name="email" type="text" value="<?php echo esc_attr(_fv('form_widget', 'email')); ?>" placeholder="<?php esc_attr_e('Email', 'selio'); ?>" />
                            </div>
                             <div class="form-field">
                                <input class="" id="username" name="username" type="text" value="<?php echo esc_attr(_fv('form_widget', 'username')); ?>" placeholder="<?php esc_attr_e('Username', 'selio'); ?>" />
                            </div>
                             <div class="form-field">
                                <input class="" id="password" name="password" type="password" value="" placeholder="<?php esc_attr_e('Password', 'selio'); ?>" />
                            </div>
                             <div class="form-field">
                                <input class="" id="re_password" name="re_password" type="password" value="" placeholder="<?php esc_attr_e('Re-enter password', 'selio'); ?>" />
                            </div>
                             <div class="form-field">
                                <input class="hidden" id="widget_id" name="widget_id" type="text" value="register" />
                            </div>
                             <div class="form-field-captcha">
                                    <?php esc_viewe(_recaptcha(TRUE)); ?>
                            </div>
                            <div class="form-cp">
                                <div class="form-field">
                                    <div class="input-field">
                                        <input type="checkbox" name="registr_terms" required="" id="registr_terms">
                                        <label for="registr_terms">
                                            <span></span>
                                            <small><?php echo esc_html__('I agree with terms', 'selio'); ?></small>
                                        </label>
                                    </div>
                                </div>
                                <a href="#log-in-form" title="<?php echo esc_attr__('Have an account?', 'selio'); ?>" class="signin-op"><?php echo esc_html__('Have an account?', 'selio'); ?></a>
                            </div>
                            <button type="submit" class="btn2"><?php echo esc_attr__('Create Acoount', 'selio'); ?></button>
                            <input class="hidden" id="widget_id_login_create" name="widget_id" type="text" value="login" />
                        </form>
                        
                        <?php if (selio_plugin_call::sw_settings('facebook_login_enabled') == '1' && selio_plugin_call::sw_settings('facebook_app_id') != ''): ?>
                            <a href="<?php echo esc_url($facebook_login_url); ?>" class="fb-btn"><i class="fa fa-facebook" aria-hidden="true"></i><?php echo esc_html__('Sign in with facebook', 'selio'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- End Sign In -->
            </div>
    </div>
<?php endif; ?>