
<h1><?php echo __('Selio Admin UI Settings','sw_win'); ?> </h1>

<div class="wrap">
    <form method="post" action="">
        <input id="role_user" name="Selio_admin_ui_form" type="hidden" class="hidden" value="true">
        <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="blogdescription"><?php echo __('Enable ADMIN UI for user`s roles','sw_win'); ?></label></th>
                <td>
                    <p>
                        <input id="role_admin" name="roles[administrator]" type="checkbox" class="regular-text code"  <?php checked( $role_admin, 1 ); ?> value="true">
                        <label for="role_admin"><?php echo __('Administrator','sw_win'); ?></label>
                    </p>
                    <p>
                        <input id="role_user" name="roles[user]" type="checkbox" class="regular-text code"  <?php checked( $role_user, 1 ); ?> value="true">
                        <label for="role_user"><?php echo __('User','sw_win'); ?></label>
                    </p>
                    <p class="description" id="tagline-description"><?php echo __('These users will be have ADMIN UI instead default wp styles','sw_win'); ?></p>
                </td>
            </tr>
        </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>