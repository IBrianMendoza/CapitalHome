<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Selio
 * @since 1.0
 * @version 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>
<?php
$pings = get_comments(array(
    'status' => 'approve',
    'type' => 'pings',
    'post_id' => get_the_ID(),
        ));
?>
<?php if (!empty($pings)): ?>
    <div class="thumbnail thumbnail-property b thumbnail-blog-open  thumbnail-blog-single ">
        <div class="caption caption-blog">
            <h3 class="p-title"><?php esc_html_e('Pingbacks And Trackbacks', 'selio'); ?></h3>                        
            <div class="description">

                <ol class="ping-list">
                    <?php
                    wp_list_comments(
                            array(
                                'type' => 'pings',
                                'short_ping' => true,
                                'style' => 'ol',
                            )
                    );
                    ?>
                </ol>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="comment-section comment_p-section">
    <?php // Check for have_comments().
    if (have_comments()) :
        ?>
        <h3 class="p-title"><?php esc_html_e('Comments', 'selio'); ?></h3>

        <ul>
            <?php
            wp_list_comments(array(
                'avatar_size' => 100,
                'style' => 'ul',
                'short_ping' => true,
                'reply_text' => esc_html__('Reply', 'selio'),
                'callback' => 'selio_comment',
                'type' => 'comment',
            ));
            ?>
        </ul>
        <?php
        the_comments_pagination(array(
            'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous', 'selio') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'selio') . '</span>',
        ));

    endif;

    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
    ?>

    <p class="no-comments"><?php esc_html_e('Comments are closed.', 'selio'); ?></p>
    <?php else: ?>
    <div class="post-comment-sec" id="comment-form">
        <?php
        $required_text = '';

        $fields = array(
            'author' =>
            '<div class="row"><div class="col-lg-4 col-md-4 pl-0"><div class="form-field">' .
            '<input id="author" name="author" placeholder="' . esc_attr__('Your Name', 'selio') . ( $req ? '*' : '' ) . '" type="text" value="' . esc_attr($commenter['comment_author']) .
            '" /></div></div>',
            'email' =>
            '<div class="col-lg-4 col-md-4"><div class="form-field">' .
            '<input id="email" name="email" type="text" placeholder="' . esc_attr__('Email', 'selio') . ( $req ? '*' : '' ) . '" value="' . esc_attr($commenter['comment_author_email']) .
            '"  /></div></div> ',
            'url' =>
            '<div class="col-lg-4 col-md-4 pr-0"><div class="form-field">' .
            '<input id="url" name="url" type="text"  placeholder="' . esc_attr__('Website', 'selio') . ( $req ? '*' : '' ) . '" value="' . esc_attr($commenter['comment_author_url']) .
            '"  /></div></div></div> ',
        );

        $args = array(
            'id_form' => 'commentform',
            'class_form' => 'selio-form',
            'title_reply_before' => '<h3 id="reply-title" class="p-title">',
            'title_reply' => esc_html__('Leave A Reply', 'selio'),
            /* translators: 1: number of comments, 2: post title */
            'title_reply_to' => esc_html__('Leave a Reply to %s', 'selio'),
            'cancel_reply_link' => esc_html__('Cancel Reply', 'selio'),
            'format' => 'xhtml',
            'fields' => apply_filters('comment_form_default_fields', $fields),
            'submit_button' => '<span class="col-lg-12 pl-0 pr-0"><button  name="submit" type="submit" id="submit" class="btn-default">' . esc_html__('Post Your Reply', 'selio') . '</button></span>',
            'comment_field' => '<div class="col-lg-12 pl-0 pr-0">
                                    <div class="form-field">
                                        <textarea id="comment" name="comment" placeholder="' . esc_attr__('Post comment', 'selio') . '*" required="required"></textarea>
                                    </div>
                                </div>',
            'must_log_in' => '<p class="must-log-in">' .
            sprintf(
                    /* translators: link to post comments */
                    esc_view(__('You must be <a href="%s">logged in</a> to post a comment.', 'selio')), wp_login_url(apply_filters('the_permalink', get_permalink()))
            ) . '</p>',
            'logged_in_as' => '<p class="logged-in-as">' .
            sprintf(
                    /* translators: logged link to post comments */
                    esc_view(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'selio')), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))
            ) . '</p>',
            'comment_notes_before' => '',
            'comment_notes_after' => '',
        );

        function selio_move_comment_field_to_bottom($fields) {
            if (isset($fields['comment'])) {
                $comment_field = $fields['comment'];
                unset($fields['comment']);
                $fields['comment'] = $comment_field;
            }

            if (isset($fields['cookies'])) {
                $consent = $fields['cookies'];
                unset($fields['cookies']);
                $fields['cookies'] = '<p class="comment-form-cookies-consent input-field">'
                        . '<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">'
                        . '<label for="wp-comment-cookies-consent" class="checkbox-styles"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">'
                        . '<span class="checkmark"></span>' . esc_html__("Save my name, email, and website in this browser for the next time I comment", "selio") . '</label>'
                        . '</p>';
            }
            return $fields;
        }

        add_filter('comment_form_fields', 'selio_move_comment_field_to_bottom');
        comment_form($args);
        ?>
    </div>
<?php
endif;
?>
</div><!--comment-section end-->
