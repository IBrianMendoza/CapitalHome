<?php 
add_action('admin_menu', 'selio_admin_custom_menu');

function selio_admin_custom_menu() {
    add_menu_page(__('Selio Admin UI Settings', 'sw_win'), __('Selio Admin UI', 'sw_win'), 'manage_options', 'selio-admin-ui', 'selio_admin_ui','', 32);
}

function selio_admin_ui() {
    
    $options = get_option( 'selio_admin_ui' );
    
    $role_admin =0;
    if($options && isset($options['roles']) && isset($options['roles']['administrator']))
        $role_admin = 1;
    
    $role_user = 0;
    if($options && isset($options['roles']) && isset($options['roles']['user']))
        $role_user = 1;
    
    if (isset( $_POST['selio_admin_ui_form'] ) ) { // WPCS: CSRF ok.
        $post = $_POST;
        $selio_admin_ui = array();
        if(isset($post['roles'])) {
            $selio_admin_ui['roles'] = $post['roles'];
        }
        update_site_option( 'selio_admin_ui' , $selio_admin_ui);
        
        $role_admin =0;
        if($post && isset($post['roles']) && isset($post['roles']['administrator']))
            $role_admin = 1;

        $role_user = 0;
        if($post && isset($post['roles']) && isset($post['roles']['user']))
            $role_user = 1;
    }
    
    include plugin_dir_path( __FILE__ ) . 'pages/settings.php';
}